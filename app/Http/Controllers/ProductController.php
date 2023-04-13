<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Result;
use App\Models\Tags;

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
        if ($request->ingredients != "") {
            $name = "ingredients Scan";
        } elseif ($request->product_name != "") {
            $name = $request->product_name;
        }
        $result = Result::where(['device_id' => $request->device_id, 'product_name' => $name])->first();
        // if ($result) {
        //     return $this->sendError('You have already searched for this product, Check your history to view product details.');
        // }
        $restrictedTags = Tags::pluck('tag_name')->toArray();
        $client = new Client();
        // Check If request has product_code & != ""
        if ($request->ingredients != "") {
            $ingredients = $request->input('ingredients');

            $allIngredients = array();
            $restrictedIngredients = array();

            // Remove restricted tags and add them to $restrictedIngredients
            foreach ($restrictedTags as $tag) {
                if (strpos($ingredients, $tag) !== false) {
                    $ingredients = str_replace($tag, '', $ingredients);
                    $restrictedIngredients[] = $tag;
                }
            }

            // Add remaining ingredients to $allIngredients
            $remainingIngredients = explode('•', $ingredients);
            foreach ($remainingIngredients as $ingredient) {
                $allIngredients[] = trim($ingredient);
            }
            // dd($allIngredients);

            // Get the common elements between both arrays
            // $restrictedIngredients = array_intersect($request->ingredients, $restrictedTags);
            // $restrictedIngredients = array_values($restrictedIngredients);
            // // Remove the common elements from the first array
            // $allIngredients = array_diff($request->ingredients, $restrictedIngredients);
            // $allIngredients = array_values($allIngredients);
            if (empty($restrictedIngredients)) {
                $harmful = 0;
            } else {
                $harmful = 1;
            }

            $success = [];
            $success['product_name'] = "Ingredients Scan";
            $success['product_img'] = "https://kodextech.net/tulip/public/assets/images/tulip.svg";
            $success['is_harmful'] = $harmful;
            $success['ingredients'] = $allIngredients;
            $success['restrictedIngredients'] = $restrictedIngredients;
            Result::create([
                'product_name' => "Ingredients Scan",
                'product_img' => "https://kodextech.net/tulip/public/assets/images/tulip.svg",
                'ingredients' => $allIngredients,
                'device_id' => $request->device_id,
                'is_harmful' => $harmful,
                'status' => 1
            ]);
            return $this->sendResponse($success, 'Found Successfully');

            // $api_key = 'f55ken5drokv7g9jzjhwpoqha78bt3';
            // $url = 'https://api.barcodelookup.com/v3/products?barcode='. $request->product_code .'&formatted=y&key=' . $api_key;

            // $ch = curl_init(); // Use only one cURL connection for multiple queries

            // $data = $this->get_data($url, $ch);

            // $response = array();
            // $response = json_decode($data);
            // // start
            // if(isset($response->products[0]->title) && (isset($response->products[0]->ingredients) && $response->products[0]->ingredients != '')){
            //     $proname = $response->products[0]->title;
            //     $ingredients = $response->products[0]->ingredients;
            //     $proingredients = explode(', ', $ingredients);
            //     $imgUrls = 'image-urls';
            //     if(isset($response->products[0]->images) && !empty($response->products[0]->images)){
            //         $proimage = $response->products[0]->images[0];
            //     }

            //     $cosmeticIngredients = [];
            //     foreach ($proingredients as $ingredients) {
            //         $cosmeticIngredients[] = $ingredients;
            //     }
            //     // Get the common elements between both arrays
            //     $restrictedIngredients = array_intersect($cosmeticIngredients, $restrictedTags);
            //     // Remove the common elements from the first array
            //     $cosmeticIngredients = array_diff($cosmeticIngredients, $restrictedIngredients);
            //     if (empty($restrictedIngredients)) {
            //         $harmful = 0;
            //     } else {
            //         $harmful = 1;
            //     }

            //     $successBeauty = [];
            //     $successBeauty['product_name'] = $proname ?? '';
            //     $successBeauty['product_img'] = $proimage ?? '';
            //     $successBeauty['is_harmful'] = $harmful;
            //     $successBeauty['ingredients'] = $cosmeticIngredients;
            //     $successBeauty['restrictedIngredients'] = $restrictedIngredients;
            //     Result::create([
            //         'product_name' => $name,
            //         'product_img' => $proimage ?? '',
            //         'ingredients' => $cosmeticIngredients,
            //         'device_id' => $request->device_id,
            //         'is_harmful' => $harmful,
            //         'status' => 1
            //     ]);
            //     return $this->sendResponse($successBeauty, 'Found Successfully');
            // }
            // // $urlFood = 'https://world.openfoodfacts.org/api/v0/product/' . $request->product_code . '.json';
            // // $responseFood = $client->request('GET', $urlFood);
            // // $responseFood = json_decode($responseFood->getBody()->getContents(), true);
            // // if ($responseFood['status'] == 1 && isset($responseFood['product']['ingredients'])) {
            // //     $foodIngredients = [];
            // //     foreach ($responseFood['product']['ingredients'] as $ingredients) {
            // //         $foodIngredients[] = trim($ingredients['id'], 'en:fr:');
            // //     }
            // //     // Get the common elements between both arrays
            // //     $restrictedIngredients = array_intersect($foodIngredients, $restrictedTags);
            // //     // Remove the common elements from the first array
            // //     $foodIngredients = array_diff($foodIngredients, $restrictedIngredients);
            // //     if (empty($restrictedIngredients)) {
            // //         $harmful = 0;
            // //     } else {
            // //         $harmful = 1;
            // //     }
            // //     $successFood = [];
            // //     $successFood['product_name'] = $responseFood['product']['product_name'] ?? '';
            // //     $successFood['product_img'] = $responseFood['product']['image_front_url'] ?? '';
            // //     $successFood['is_harmful'] = $harmful;
            // //     $successFood['ingredients'] = $foodIngredients;
            // //     $successFood['restrictedIngredients'] = $restrictedIngredients;
            // //     Result::create([
            // //         'product_name' => $name,
            // //         'product_img' => $responseFood['product']['image_front_url'] ?? '',
            // //         'ingredients' => $foodIngredients,
            // //         'device_id' => $request->device_id,
            // //         'is_harmful' => 0,
            // //         'status' => 1,

            // //     ]);
            // //     return $this->sendResponse($successFood, 'Found Successfully');
            // else {
            //     $urlBeauty = 'https://world.openbeautyfacts.org/api/v0/product/' . $request->product_code . '.json';
            //     // Check for Cosmetic using OpenBeautyFact API
            //     $responseCosmetic = $client->request('GET', $urlBeauty);
            //     $responseCosmetic = json_decode($responseCosmetic->getBody()->getContents(), true);
            //     if ($responseCosmetic['status'] == 1 && isset($responseCosmetic['product']['ingredients'])) {
            //         $cosmeticIngredients = [];
            //         foreach ($responseCosmetic['product']['ingredients'] as $ingredients) {
            //             $cosmeticIngredients[] = trim($ingredients['id'], 'en:fr:');
            //         }
            //         // Get the common elements between both arrays
            //         $restrictedIngredients = array_intersect($cosmeticIngredients, $restrictedTags);
            //         // Remove the common elements from the first array
            //         $cosmeticIngredients = array_diff($cosmeticIngredients, $restrictedIngredients);
            //         if (empty($restrictedIngredients)) {
            //             $harmful = 0;
            //         } else {
            //             $harmful = 1;
            //         }
            //         $successBeauty = [];
            //         $successBeauty['product_name'] = $responseCosmetic['product']['product_name'] ?? '';
            //         $successBeauty['product_img'] = $responseCosmetic['product']['image_front_url'] ?? '';
            //         $successBeauty['is_harmful'] = 0;
            //         $successBeauty['ingredients'] = $cosmeticIngredients;
            //         $successBeauty['restrictedIngredients'] = $restrictedIngredients;
            //         Result::create([
            //             'product_name' => $name,
            //             'product_img' => $responseCosmetic['product']['image_front_url'] ?? '',
            //             'ingredients' => $cosmeticIngredients,
            //             'device_id' => $request->device_id,
            //             'is_harmful' => $harmful,
            //             'status' => 1

            //         ]);
            //         return $this->sendResponse($successBeauty, 'Found Successfully');
            //     }
            //     // Sephora API starts HERE
            //     else{
            //         $curl = curl_init(); //3378872105602
            //         // $barcode = '3378872105602';
            //         curl_setopt_array($curl, [
            //             CURLOPT_URL => "https://sephora.p.rapidapi.com/products/v2/search-by-barcode?upcs=' .$request->product_code. '&country=SG&language=en-SG",
            //             CURLOPT_RETURNTRANSFER => true,
            //             CURLOPT_FOLLOWLOCATION => true,
            //             CURLOPT_ENCODING => "",
            //             CURLOPT_MAXREDIRS => 10,
            //             CURLOPT_TIMEOUT => 30,
            //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //             CURLOPT_CUSTOMREQUEST => "GET",
            //             CURLOPT_HTTPHEADER => [
            //                 "X-RapidAPI-Host: sephora.p.rapidapi.com",
            //                 "X-RapidAPI-Key: e6bc3ce822msh8f140b8144727a9p11e011jsnb59211620217"
            //             ],
            //         ]);

            //         $response = curl_exec($curl);
            //         $err = curl_error($curl);

            //         curl_close($curl);
            //         $response = json_decode($response);
            //         if(isset($response->data)){
            //             $product_id = $response->data->attributes->{'product-id'} ?? '';

            //             $curl = curl_init();

            //             curl_setopt_array($curl, [
            //                 CURLOPT_URL => "https://sephora.p.rapidapi.com/products/v2/detail?id=$product_id&country=SG&language=en-SG",
            //                 CURLOPT_RETURNTRANSFER => true,
            //                 CURLOPT_FOLLOWLOCATION => true,
            //                 CURLOPT_ENCODING => "",
            //                 CURLOPT_MAXREDIRS => 10,
            //                 CURLOPT_TIMEOUT => 30,
            //                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //                 CURLOPT_CUSTOMREQUEST => "GET",
            //                 CURLOPT_HTTPHEADER => [
            //                     "X-RapidAPI-Host: sephora.p.rapidapi.com",
            //                     "X-RapidAPI-Key: e6bc3ce822msh8f140b8144727a9p11e011jsnb59211620217"
            //                 ],
            //             ]);

            //             $response = curl_exec($curl);
            //             $response = json_decode($response);
            //             $image = $response->data->attributes->{'image-urls'}[0];
            //             $successBeauty = [];
            //             $successBeauty['product_name'] = $response->data->attributes->{'name'} ?? '';
            //             $successBeauty['product_img'] = $response->data->attributes->{'image-urls'}[0] ?? '';
            //             $successBeauty['is_harmful'] = 0;
            //             $successBeauty['ingredients'] = $response->data->attributes->{'ingredients'} ?? '';
            //             $successBeauty['restrictedIngredients'] = [];
            //             Result::create([
            //                 'product_name' => $name,
            //                 'product_img' => $response->data->attributes->{'image-urls'}[0] ?? '',
            //                 'ingredients' => $response->data->attributes->{'ingredients'} ?? '',
            //                 'device_id' => $request->device_id,
            //                 'is_harmful' => 0,
            //                 'status' => 1
            //             ]);
            //             return $this->sendResponse($successBeauty, 'Found Successfully');

            //         }
            //     }
            // }
        } elseif ($request->product_name != "") {
            // $urlFood = 'https://world.openfoodfacts.org/cgi/search.pl?search_terms=' . $request->product_name . '&search_simple=1&action=process&json=1&page_size=1';
            // // First Check for Food using OpenFoodFact API
            // $responseFood = $client->request('GET', $urlFood);
            // $responseFood = json_decode($responseFood->getBody()->getContents(), true);
            // // return $responseFood;
            // if (!empty($responseFood['products']) && isset($responseFood['products'][0]['ingredients'])) {
            //     $foodIngredients = [];
            //     foreach ($responseFood['products'][0]['ingredients'] as $ingredients) {
            //         $foodIngredients[] = trim($ingredients['id'], 'en:fr:');
            //     }
            //     // Get the common elements between both arrays
            //     $restrictedIngredients = array_intersect($foodIngredients, $restrictedTags);
            //     // Remove the common elements from the first array
            //     $foodIngredients = array_diff($foodIngredients, $restrictedIngredients);
            //     if (empty($restrictedIngredients)) {
            //         $harmful = 0;
            //     } else {
            //         $harmful = 1;
            //     }
            //     $successFood = [];
            //     $successFood['product_name'] = $responseFood['products'][0]['product_name'] ?? '';
            //     $successFood['product_img'] = $responseFood['products'][0]['image_front_url'] ?? '';
            //     $successFood['is_harmful'] = $harmful;
            //     $successFood['ingredients'] = $foodIngredients;
            //     $successFood['restrictedIngredients'] = $restrictedIngredients;
            //     Result::create([
            //         'product_name' => $name,
            //         'product_img' => $responseFood['products'][0]['image_front_url'] ?? '',
            //         'ingredients' => $foodIngredients,
            //         'device_id' => $request->device_id,
            //         'is_harmful' => 0,
            //         'status' => 1

            //     ]);
            //     return $this->sendResponse($successFood, 'Found Successfully');
            // } else {
            $urlBeauty = 'https://world.openbeautyfacts.org/cgi/search.pl?search_terms=' . $request->product_name . '&search_simple=1&action=process&json=1&page_size=1';
            // Check for Cosmetic using OpenBeautyFact API
            $responseCosmetic = $client->request('GET', $urlBeauty);
            $responseCosmetic = json_decode($responseCosmetic->getBody()->getContents(), true);
            // return $responseCosmetic['products'];
            if (!empty($responseCosmetic['products'])) {
                $cosmeticIngredients = [];
                if (isset($responseCosmetic['products'][0]['ingredients'])) {
                    foreach ($responseCosmetic['products'][0]['ingredients'] as $ingredients) {
                        $cosmeticIngredients[] = trim($ingredients['text'], 'en:fr:');
                    }
                    // Get the common elements between both arrays
                    $restrictedIngredients = array_intersect($cosmeticIngredients, $restrictedTags);
                    $restrictedIngredients = array_values($restrictedIngredients);

                    // Remove the common elements from the first array
                    $cosmeticIngredients = array_diff($cosmeticIngredients, $restrictedIngredients);
                    $cosmeticIngredients = array_values($cosmeticIngredients);

                    if (empty($restrictedIngredients)) {
                        $harmful = 0;
                    } else {
                        $harmful = 1;
                    }
                    $successBeauty = [];
                    $successBeauty['product_name'] = $responseCosmetic['products'][0]['product_name'] ?? '';
                    $successBeauty['product_img'] = $responseCosmetic['products'][0]['image_front_small_url'] ?? '';
                    $successBeauty['is_harmful'] = $harmful;
                    $successBeauty['ingredients'] = $cosmeticIngredients;
                    $successBeauty['restrictedIngredients'] = $restrictedIngredients;
                    Result::create([
                        'product_name' => $name,
                        'product_img' => $responseCosmetic['products'][0]['image_front_small_url'] ?? '',
                        'ingredients' => $cosmeticIngredients,
                        'device_id' => $request->device_id,
                        'is_harmful' => $harmful,
                        'status' => 1
                    ]);
                    return $this->sendResponse($successBeauty, 'Found Successfully');
                } else {
                    // Sephora Name API Starts Here
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://sephora.p.rapidapi.com/v2/auto-complete?query='. $request->product_name .'&country=SG&language=en-SG",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
                            "X-RapidAPI-Host: sephora.p.rapidapi.com",
                            "X-RapidAPI-Key: e6bc3ce822msh8f140b8144727a9p11e011jsnb59211620217"
                        ],
                    ]);

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    if ($response != null) {
                        foreach ($response->included as $product) {
                            if (isset($product->attributes->name) && (isset($product->attributes->ingredients) && $product->attributes->ingredients != null)) {
                                $proname = $product->attributes->name;
                                $ingredients = $product->attributes->ingredients;
                                $proingredients = explode(', ', $ingredients);
                                $imgUrls = 'image-urls';
                                if (isset($product->attributes->$imgUrls) && !empty($product->attributes->$imgUrls)) {
                                    $proimage = $product->attributes->$imgUrls[0];
                                }

                                $cosmeticIngredients = [];
                                foreach ($proingredients as $ingredients) {
                                    $cosmeticIngredients[] = $ingredients;
                                }
                                // Get the common elements between both arrays
                                $restrictedIngredients = array_intersect($cosmeticIngredients, $restrictedTags);
                                $restrictedIngredients = array_values($restrictedIngredients);

                                // Remove the common elements from the first array
                                $cosmeticIngredients = array_diff($cosmeticIngredients, $restrictedIngredients);
                                $cosmeticIngredients = array_values($cosmeticIngredients);

                                if (empty($restrictedIngredients)) {
                                    $harmful = 0;
                                } else {
                                    $harmful = 1;
                                }
                                $successBeauty = [];
                                $successBeauty['product_name'] = $proname ?? '';
                                $successBeauty['product_img'] = $proimage ?? '';
                                $successBeauty['is_harmful'] = $harmful;
                                $successBeauty['ingredients'] = $cosmeticIngredients;
                                $successBeauty['restrictedIngredients'] = $restrictedIngredients;
                                Result::create([
                                    'product_name' => $name,
                                    'product_img' => $proimage ?? '',
                                    'ingredients' => $cosmeticIngredients,
                                    'device_id' => $request->device_id,
                                    'is_harmful' => $harmful,
                                    'status' => 1
                                ]);
                                return $this->sendResponse($successBeauty, 'Found Successfully');
                            } else {
                                continue;
                            }
                        }
                    }
                }
            } else {
                // Sephora Name API Starts Here
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://sephora.p.rapidapi.com/v2/auto-complete?query='. $request->product_name .'&country=SG&language=en-SG",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                        "X-RapidAPI-Host: sephora.p.rapidapi.com",
                        "X-RapidAPI-Key: e6bc3ce822msh8f140b8144727a9p11e011jsnb59211620217"
                    ],
                ]);

                $response = curl_exec($curl);
                $response = json_decode($response);
                if ($response != null) {
                    foreach ($response->included as $product) {
                        if (isset($product->attributes->name) && (isset($product->attributes->ingredients) && $product->attributes->ingredients != null)) {
                            $proname = $product->attributes->name;
                            $ingredients = $product->attributes->ingredients;
                            $proingredients = explode(', ', $ingredients);
                            $imgUrls = 'image-urls';
                            if (isset($product->attributes->$imgUrls) && !empty($product->attributes->$imgUrls)) {
                                $proimage = $product->attributes->$imgUrls[0];
                            }

                            $cosmeticIngredients = [];
                            foreach ($proingredients as $ingredients) {
                                $cosmeticIngredients[] = $ingredients;
                            }
                            // Get the common elements between both arrays
                            $restrictedIngredients = array_intersect($cosmeticIngredients, $restrictedTags);
                            $restrictedIngredients = array_values($restrictedIngredients);

                            // Remove the common elements from the first array
                            $cosmeticIngredients = array_diff($cosmeticIngredients, $restrictedIngredients);
                            $cosmeticIngredients = array_values($cosmeticIngredients);

                            if (empty($restrictedIngredients)) {
                                $harmful = 0;
                            } else {
                                $harmful = 1;
                            }
                            $successBeauty = [];
                            $successBeauty['product_name'] = $proname ?? '';
                            $successBeauty['product_img'] = $proimage ?? '';
                            $successBeauty['is_harmful'] = $harmful;
                            $successBeauty['ingredients'] = $cosmeticIngredients;
                            $successBeauty['restrictedIngredients'] = $restrictedIngredients;
                            Result::create([
                                'product_name' => $name,
                                'product_img' => $proimage ?? '',
                                'ingredients' => $cosmeticIngredients,
                                'device_id' => $request->device_id,
                                'is_harmful' => $harmful,
                                'status' => 1
                            ]);
                            return $this->sendResponse($successBeauty, 'Found Successfully');
                        } else {
                            continue;
                        }
                    }
                }
            }
        }
        return $this->sendError('No data found against this product.');
    }

    public function addTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($request->has('id')) {
            $tag = Tags::where('id', $request->id)->update(['tag_name' => $request->tag_name]);
        }
        $tag = Tags::firstOrNew(array('tag_name' => $request->tag_name));
        $status = $tag->save();
        if ($status) {
            return $this->sendResponse($tag, 'Tag Added to restricted tags successfully.');
        }
        return $this->sendError('Something went wrong');
    }

    public function history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $restrictedTags = Tags::pluck('tag_name')->toArray();
        $results = Result::where('device_id', $request->device_id)->get();
        if (!empty($results)) {
            foreach ($results as $result) {
                // Get the common elements between both arrays
                $restrictedIngredients = array_intersect($result['ingredients'], $restrictedTags);
                $restrictedIngredients = array_values($restrictedIngredients);
                // Remove the common elements from the first array
                $result['ingredients'] = array_diff($result['ingredients'], $restrictedIngredients);
                $result['ingredients'] = array_values($result['ingredients']);
                $result['restrictedIngredients'] = $restrictedIngredients;
                if (empty($restrictedIngredients)) {
                    $result['is_harmful'] = 0;
                } else {
                    $result['is_harmful']  = 1;
                }
            }
            return $this->sendResponse($results, 'History Data.');
        }
        return $this->sendError('You have no history.');
    }

    public function testCurl(Request $request)
    {

        // $curl = curl_init();
        // curl_setopt_array($curl, [
        //     CURLOPT_URL => "https://sephora.p.rapidapi.com/products/v2/search-by-barcode?upcs=3378872105602&country=SG&language=en-SG",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => [
        //         "X-RapidAPI-Host: sephora.p.rapidapi.com",
        //         "X-RapidAPI-Key: e6bc3ce822msh8f140b8144727a9p11e011jsnb59211620217"
        //     ],
        // ]);

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);
        // $response = json_decode($response);
        // if(isset($response->data)){
        //     $product_id = $response->data->attributes->{'product-id'} ?? '';

        //     $curl = curl_init();

        //     curl_setopt_array($curl, [
        //         CURLOPT_URL => "https://sephora.p.rapidapi.com/products/v2/detail?id=$product_id&country=SG&language=en-SG",
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_ENCODING => "",
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 30,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => "GET",
        //         CURLOPT_HTTPHEADER => [
        //             "X-RapidAPI-Host: sephora.p.rapidapi.com",
        //             "X-RapidAPI-Key: e6bc3ce822msh8f140b8144727a9p11e011jsnb59211620217"
        //         ],
        //     ]);

        //     $response = curl_exec($curl);
        //     $response = json_decode($response);
        //     $image = $response->data->attributes->{'name'};
        //     print_r($response);

        // }


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://sephora.p.rapidapi.com/v2/auto-complete?query=eyeshadows&country=SG&language=en-SG",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: sephora.p.rapidapi.com",
                "X-RapidAPI-Key: e6bc3ce822msh8f140b8144727a9p11e011jsnb59211620217"
            ],
        ]);

        $response = curl_exec($curl);
        $response = json_decode($response);
        foreach ($response->included as $product) {
            if (isset($product->attributes->name) && (isset($product->attributes->ingredients) && $product->attributes->ingredients != null)) {
                $proname = $product->attributes->name;
                $ingredients = $product->attributes->ingredients;
                $proingredients = explode(', ', $ingredients);
                $imgUrls = 'image-urls';
                if (isset($product->attributes->$imgUrls) && !empty($product->attributes->$imgUrls)) {
                    $proimage = $product->attributes->$imgUrls[0];
                }

                $cosmeticIngredients = [];
                foreach ($proingredients as $ingredients) {
                    $cosmeticIngredients[] = $ingredients;
                }
                // Get the common elements between both arrays
                // $restrictedIngredients = array_intersect($cosmeticIngredients, $restrictedTags);
                // // Remove the common elements from the first array
                // $cosmeticIngredients = array_diff($cosmeticIngredients, $restrictedIngredients);
                // if (empty($restrictedIngredients)) {
                //     $harmful = 0;
                // } else {
                //     $harmful = 1;
                // }
                $successBeauty = [];
                $successBeauty['product_name'] = $proname ?? '';
                $successBeauty['product_img'] = $proimage ?? '';
                // $successBeauty['is_harmful'] = $harmful;
                $successBeauty['ingredients'] = $cosmeticIngredients;
                // $successBeauty['restrictedIngredients'] = $restrictedIngredients;
                // Result::create([
                //     'product_name' => $name,
                //     'product_img' => $proimage ?? '',
                //     'ingredients' => $cosmeticIngredients,
                //     'device_id' => $request->device_id,
                //     'is_harmful' => $harmful,
                //     'status' => 1
                // ]);
                return $this->sendResponse($successBeauty, 'Found Successfully');
            } else {
                continue;
            }
        }
        return $response->included;
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function barcodelookup()
    {
        $api_key = 'f55ken5drokv7g9jzjhwpoqha78bt3';
        $url = 'https://api.barcodelookup.com/v3/products?barcode=3614272049529&formatted=y&key=' . $api_key;

        $ch = curl_init(); // Use only one cURL connection for multiple queries

        $data = $this->get_data($url, $ch);

        $response = array();
        $response = json_decode($data);
        echo '<strong>Barcode Number:</strong> ' . $response->products[0]->ingredients . '<br><br>';

        echo '<strong>Title:</strong> ' . $response->products[0]->title . '<br><br>';

        echo '<strong>Entire Response:</strong><pre>';
        print_r($response);
        echo '</pre>';
    }

    public function get_data($url, $ch)
    {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
