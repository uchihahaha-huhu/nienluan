<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductChatController extends Controller
{
    public function chat(Request $request)
    {
        $question = $request->input('question');
        if (!$question) {
            return response()->json(['error' => 'Missing question parameter'], 400);
        }

        $apiKey = config('services.openrouter.api_key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'OpenRouter API key is not configured'], 500);
        }

        // Lấy danh sách toàn bộ sản phẩm active, đầy đủ thông tin để gửi AI
        $allProducts = Product::where('pro_active', 1)
            ->select('id', 'pro_name', 'pro_price', 'pro_avatar', 'pro_slug', 'pro_sale')
            ->limit(50) // Giới hạn số lượng lớn để AI chọn lọc từ đây
            ->get();

        // Tính giá sau sale cho từng sản phẩm (nếu có)
        $allProducts->transform(function ($product) {
            if ($product->pro_sale && $product->pro_sale > 0) {
                $priceAfterSale = ($product->pro_price * (100 - $product->pro_sale)) / 100;
                $product->pro_price = round($priceAfterSale);
            }
            return $product;
        });

        // Chuyển danh sách sản phẩm ra text dạng JSON đơn giản cho AI đọc (có thể dùng json_encode)
        $productsJsonText = $allProducts->map(function ($p) {
            return [
                'name' => $p->pro_name,
                'slug' => $p->pro_slug,
                'price' => $p->pro_price,
            ];
        })->toJson(JSON_UNESCAPED_UNICODE);

        // Tạo prompt yêu cầu AI trả lời và trả về danh sách sản phẩm gợi ý dưới dạng JSON
        $systemMessage = 'Bạn là trợ lý hữu ích, trả lời bằng tiếng Việt. Dựa trên danh sách sản phẩm hiện có, hãy gợi ý những sản phẩm phù hợp nhất với câu hỏi của người dùng. '
            . 'Khi gợi ý, vui lòng trả về một đoạn JSON chứa danh sách tên sản phẩm (pro_name) bạn chọn, đồng thời trả lời chi tiết đằng sau.';

        $userMessage = "Danh sách sản phẩm hiện có (dưới dạng JSON):\n" . $productsJsonText . "\n\nCâu hỏi của người dùng: " . $question
            . "\nVui lòng trả về kết quả trong định dạng sau:\n"
            . "{ \"suggested_products\": [\"Tên sản phẩm 1\", \"Tên sản phẩm 2\", ...], \"reply\": \"Đoạn trả lời chi tiết ...\" }";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'HTTP-Referer'  => url('/'),
                'X-Title'       => 'YourAppName',
            ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'anthropic/claude-3.5-haiku',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to call OpenRouter API'], 500);
            }

            $rawContent = $response->json('choices.0.message.content');

            // Cố gắng parse đoạn JSON do AI trả về
            $jsonStart = strpos($rawContent, '{');
            $jsonEnd = strrpos($rawContent, '}');
            $jsonText = $jsonStart !== false && $jsonEnd !== false ? substr($rawContent, $jsonStart, $jsonEnd - $jsonStart + 1) : null;

            $jsonData = null;
            if ($jsonText) {
                $jsonData = json_decode($jsonText, true);
            }

            // Mặc định trả về toàn bộ text AI như reply
            $chatReply = $rawContent;
            $suggestedProducts = collect();

            if ($jsonData && isset($jsonData['suggested_products']) && is_array($jsonData['suggested_products'])) {
                // Tìm các sản phẩm tương ứng trong database theo tên hoặc slug
                $names = $jsonData['suggested_products'];

                // Query lấy các sản phẩm matching tên (pro_name)
                $suggestedProducts = Product::where('pro_active', 1)
                    ->whereIn('pro_name', $names)
                    ->select('id', 'pro_name', 'pro_price', 'pro_avatar', 'pro_slug', 'pro_sale')
                    ->get();

                // Tính giá sale lại nếu có
                $suggestedProducts->transform(function ($product) {
                    if ($product->pro_sale && $product->pro_sale > 0) {
                        $priceAfterSale = ($product->pro_price * (100 - $product->pro_sale)) / 100;
                        $product->pro_price = round($priceAfterSale);
                    }
                    return $product;
                });

                $chatReply = $jsonData['reply'] ?? $chatReply;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'OpenRouter API exception: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'chat_reply' => $chatReply,
            'suggested_products' => $suggestedProducts,
        ]);
    }
}
