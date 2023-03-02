<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Result;

class ProductController extends Controller
{
    use ResponseTrait;
    // Edamam API Working
    // public function getIngredients(Request $request)
    // {
    //     // $client = new Client();
    //     // $response = $client->request('GET', 'https://api.edamam.com/api/food-database/v2/parser', [
    //     //     'query' => [
    //     //         'app_id' => '53f2d847',
    //     //         'app_key' => '079e863513e12b6f7f5bfd6d272c2d0b',
    //     //         'ingr' => $productId,
    //     //     ]
    //     // ]);

    //     // $data = json_decode($response->getBody()->getContents(), true);
    //     // dd($data);
    //     // $ingredients = $data['hints'][0]['food']['foodContentsLabel'];

    //     // return response()->json($ingredients);
    // }

    public function getIngredients(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // Check If request has product_name & != ""
        if ($request->product_code != "") {
            $name = $request->product_code;
        } elseif ($request->product_name != "") {
            $name = $request->product_name;
        }
        $result = Result::where(['device_id' => $request->device_id, 'product_name' => $name])->first();
        if ($result) {
            return $this->sendError('You have already searched for this product, Check your history to view product details.');
        }
        $client = new Client();
        // Check If request has product_code & != ""
        if ($request->product_code != "") {
            $urlFood = 'https://world.openfoodfacts.org/api/v0/product/' . $request->product_code . '.json';
            $responseFood = $client->request('GET', $urlFood);
            $responseFood = json_decode($responseFood->getBody()->getContents(), true);
            if ($responseFood['status'] == 1 && isset($responseFood['product']['ingredients'])) {
                $foodIngredients = [];
                foreach ($responseFood['product']['ingredients'] as $ingredients) {
                    $foodIngredients[] = trim($ingredients['id'], 'en:fr:');
                }
                $successFood = [];
                $successFood['product_name'] = $responseFood['product']['product_name'];
                $successFood['product_img'] = $responseFood['product']['image_front_url'];
                $successFood['is_harmful'] = 0;
                $successFood['ingredients'] = $foodIngredients;
                Result::create([
                    'product_name' => $name,
                    'product_img' => $responseFood['product']['image_front_url'],
                    'ingredients' => $foodIngredients,
                    'device_id' => $request->device_id,
                    'is_harmful' => 0,
                ]);
                return $this->sendResponse($successFood, 'Found Successfully');
            } else {
                $urlBeauty = 'https://world.openbeautyfacts.org/api/v0/product/' . $request->product_code . '.json';
                // Check for Cosmetic using OpenBeautyFact API
                $responseCosmetic = $client->request('GET', $urlBeauty);
                $responseCosmetic = json_decode($responseCosmetic->getBody()->getContents(), true);
                if ($responseCosmetic['status'] == 1 && isset($responseCosmetic['product']['ingredients'])) {
                    $cosmeticIngredients = [];
                    foreach ($responseCosmetic['product']['ingredients'] as $ingredients) {
                        $cosmeticIngredients[] = trim($ingredients['id'], 'en:fr:');
                    }
                    $successBeauty = [];
                    $successBeauty['product_name'] = $responseCosmetic['product']['product_name'];
                    $successBeauty['product_img'] = $responseCosmetic['product']['image_front_url'];
                    $successBeauty['is_harmful'] = 0;
                    $successBeauty['ingredients'] = $cosmeticIngredients;
                    Result::create([
                        'product_name' => $name,
                        'product_img' => $responseCosmetic['product']['image_front_url'],
                        'ingredients' => $cosmeticIngredients,
                        'device_id' => $request->device_id,
                        'is_harmful' => 0,
                    ]);
                    return $this->sendResponse($successBeauty, 'Found Successfully');
                }
            }
        } elseif ($request->product_name != "") {
            $urlFood = 'https://world.openfoodfacts.org/cgi/search.pl?search_terms=' . $request->product_name . '&search_simple=1&action=process&json=1&page_size=1';
            // First Check for Food using OpenFoodFact API
            $responseFood = $client->request('GET', $urlFood);
            $responseFood = json_decode($responseFood->getBody()->getContents(), true);
            // return $responseFood;
            if (!empty($responseFood['products']) && isset($responseFood['products'][0]['ingredients'])) {
                $foodIngredients = [];
                foreach ($responseFood['products'][0]['ingredients'] as $ingredients) {
                    $foodIngredients[] = trim($ingredients['id'], 'en:fr:');
                }
                $successFood = [];
                $successFood['product_name'] = $responseFood['products'][0]['product_name'];
                $successFood['product_img'] = $responseFood['products'][0]['image_front_url'];
                $successFood['is_harmful'] = 0;
                $successFood['ingredients'] = $foodIngredients;
                Result::create([
                    'product_name' => $name,
                    'product_img' => $responseFood['products'][0]['image_front_url'],
                    'ingredients' => $foodIngredients,
                    'device_id' => $request->device_id,
                    'is_harmful' => 0,
                ]);
                return $this->sendResponse($successFood, 'Found Successfully');
            } else {
                $urlBeauty = 'https://world.openbeautyfacts.org/cgi/search.pl?search_terms=' . $request->product_name . '&search_simple=1&action=process&json=1&page_size=1';
                // Check for Cosmetic using OpenBeautyFact API
                $responseCosmetic = $client->request('GET', $urlBeauty);
                $responseCosmetic = json_decode($responseCosmetic->getBody()->getContents(), true);
                // return $responseCosmetic['products'];
                if (!empty($responseCosmetic['products'])) {
                    $cosmeticIngredients = [];
                    foreach ($responseCosmetic['products'][0]['ingredients'] as $ingredients) {
                        $cosmeticIngredients[] = trim($ingredients['text'], 'en:fr:');
                    }
                    $successBeauty = [];
                    $successBeauty['product_name'] = $responseCosmetic['products'][0]['product_name'];
                    $successBeauty['product_img'] = $responseCosmetic['products'][0]['image_front_small_url'];
                    $successBeauty['is_harmful'] = 0;
                    $successBeauty['ingredients'] = $cosmeticIngredients;
                    Result::create([
                        'product_name' => $name,
                        'product_img' => $responseCosmetic['products'][0]['image_front_small_url'],
                        'ingredients' => $cosmeticIngredients,
                        'device_id' => $request->device_id,
                        'is_harmful' => 0,
                    ]);
                    return $this->sendResponse($successBeauty, 'Found Successfully');
                }
            }
        }

        Result::create([
            'product_name' => $name,
            'product_img' => 'https://assets.materialup.com/uploads/52ccfcff-ec98-4166-927d-467a9a52bdf9/preview.png',
            'ingredients' => [],
            'device_id' => $request->device_id,
            'is_harmful' => 0,
        ]);
        return $this->sendError('No data found against this product.');
    }
}
