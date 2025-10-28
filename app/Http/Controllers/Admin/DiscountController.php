<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    /**
     * Apply discount to selected products.
     */
    public function applyDiscount(Request $request)
    {
        try {
            Log::info('Discount application started', [
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Validate the request
            $validated = $request->validate([
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'exists:products,id',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
            ]);

            Log::info('Request validated successfully', ['validated' => $validated]);

            $productIds = $validated['product_ids'];
            $discountPercentage = $validated['discount_percentage'];
            $startDate = $validated['start_date'] ? now()->parse($validated['start_date']) : now();
            $endDate = $validated['end_date'] ? now()->parse($validated['end_date']) : null;

            Log::info('Parsed dates', [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'discount_percentage' => $discountPercentage
            ]);

            $updatedCount = 0;
            $errors = [];

            DB::transaction(function () use ($productIds, $discountPercentage, $startDate, $endDate, &$updatedCount, &$errors) {
                foreach ($productIds as $productId) {
                    try {
                        Log::info('Processing product', ['product_id' => $productId]);
                        
                        $product = Product::find($productId);
                        
                        if (!$product) {
                            $error = "Product with ID {$productId} not found.";
                            $errors[] = $error;
                            Log::warning($error);
                            continue;
                        }

                        Log::info('Product found', [
                            'product_id' => $product->id,
                            'title' => $product->title,
                            'current_price' => $product->price,
                            'original_price' => $product->original_price,
                            'discount_percentage' => $product->discount_percentage
                        ]);

                        // Check if product already has an active discount
                        if ($product->discount_percentage && $product->discount_start_date) {
                            $now = now();
                            $hasActiveDiscount = $now->gte($product->discount_start_date) && 
                                               (!$product->discount_end_date || $now->lte($product->discount_end_date));
                            
                            if ($hasActiveDiscount) {
                                $error = "Product '{$product->title}' already has an active discount.";
                                $errors[] = $error;
                                Log::warning($error);
                                continue;
                            }
                        }

                        // Store original price if not already set
                        if (!$product->original_price) {
                            $product->original_price = $product->price;
                            Log::info('Set original price', ['original_price' => $product->original_price]);
                        }

                        // Calculate discounted price (floored)
                        $discountedPrice = floor($product->original_price * (1 - ($discountPercentage / 100)));
                        
                        Log::info('Calculated discounted price', [
                            'original_price' => $product->original_price,
                            'discount_percentage' => $discountPercentage,
                            'discounted_price' => $discountedPrice
                        ]);

                        // Update product with discount information
                        $updateData = [
                            'discount_percentage' => $discountPercentage,
                            'discount_start_date' => $startDate,
                            'discount_end_date' => $endDate,
                            'price' => $discountedPrice,
                        ];

                        Log::info('Updating product with data', $updateData);
                        
                        $product->update($updateData);
                        $updatedCount++;

                        Log::info('Product updated successfully', ['product_id' => $product->id]);

                    } catch (\Exception $e) {
                        $error = "Error updating product {$productId}: " . $e->getMessage();
                        $errors[] = $error;
                        Log::error($error, [
                            'product_id' => $productId,
                            'exception' => $e->getTraceAsString()
                        ]);
                    }
                }
            });

            Log::info('Transaction completed', [
                'updated_count' => $updatedCount,
                'errors_count' => count($errors)
            ]);

            if ($updatedCount > 0) {
                $message = "Discount applied successfully to {$updatedCount} product" . ($updatedCount > 1 ? 's' : '') . ".";
                
                if (!empty($errors)) {
                    $message .= " " . count($errors) . " product" . (count($errors) > 1 ? 's' : '') . " skipped due to existing discounts.";
                }

                Log::info('Discount application successful', [
                    'message' => $message,
                    'updated_count' => $updatedCount,
                    'errors' => $errors
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'updated_count' => $updatedCount,
                    'errors' => $errors,
                ]);
            } else {
                Log::warning('No products were updated', ['errors' => $errors]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'No products were updated. ' . implode(' ', $errors),
                    'errors' => $errors,
                ], 422);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Unexpected error in discount application', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'type' => get_class($e)
                ]
            ], 500);
        }
    }

    /**
     * Remove discount from a product.
     */
    public function removeDiscount($productId)
    {
        try {
            \Log::info("Removing discount from product ID: {$productId}");
            
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prodotto non trovato.',
                ], 404);
            }

            // Restore original price if available
            $restorePrice = $product->original_price ?? $product->price;
            
            $product->update([
                'discount_percentage' => null,
                'discount_start_date' => null,
                'discount_end_date' => null,
                'price' => $restorePrice,
                'original_price' => null,
            ]);

            \Log::info("Discount removed successfully from product: {$product->title}");

            return response()->json([
                'success' => true,
                'message' => "Sconto rimosso con successo. Prezzo ripristinato a €{$restorePrice}.",
                'product' => $product->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Error removing discount from product {$productId}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la rimozione dello sconto.',
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    /**
     * Extend discount duration for a product.
     */
    public function extendDiscount(Request $request, $productId)
    {
        try {
            $validated = $request->validate([
                'days' => 'required|integer|min:1|max:365',
            ]);

            \Log::info("Extending discount for product ID: {$productId} by {$validated['days']} days");
            
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prodotto non trovato.',
                ], 404);
            }

            if (!$product->discount_percentage || !$product->discount_start_date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Il prodotto non ha uno sconto attivo.',
                ], 400);
            }

            $days = $validated['days'];
            $newEndDate = null;
            
            if ($product->discount_end_date) {
                // Extend existing end date
                $newEndDate = now()->parse($product->discount_end_date)->addDays($days);
            } else {
                // Set end date from now
                $newEndDate = now()->addDays($days);
            }
            
            $product->update([
                'discount_end_date' => $newEndDate,
            ]);

            \Log::info("Discount extended successfully for product: {$product->title} until {$newEndDate}");

            return response()->json([
                'success' => true,
                'message' => "Sconto esteso con successo fino al {$newEndDate->format('d/m/Y')}.",
                'product' => $product->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Error extending discount for product {$productId}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'estensione dello sconto.',
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    /**
     * Modify discount for a product.
     */
    public function modifyDiscount(Request $request, $productId)
    {
        try {
            $validated = $request->validate([
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
            ]);

            \Log::info("Modifying discount for product ID: {$productId}");
            \Log::info("New discount percentage: {$validated['discount_percentage']}%");
            
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prodotto non trovato.',
                ], 404);
            }

            $discountPercentage = $validated['discount_percentage'];
            $startDate = $validated['start_date'] ? now()->parse($validated['start_date']) : $product->discount_start_date;
            $endDate = $validated['end_date'] ? now()->parse($validated['end_date']) : $validated['end_date'];

            // Ensure we have an original price
            if (!$product->original_price) {
                $product->original_price = $product->price;
            }

            // Calculate new discounted price
            $discountedPrice = floor($product->original_price * (1 - ($discountPercentage / 100)));

            $product->update([
                'discount_percentage' => $discountPercentage,
                'discount_start_date' => $startDate,
                'discount_end_date' => $endDate,
                'price' => $discountedPrice,
            ]);

            \Log::info("Discount modified successfully for product: {$product->title}");

            return response()->json([
                'success' => true,
                'message' => "Sconto modificato con successo. Nuovo prezzo: €{$discountedPrice}.",
                'product' => $product->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Error modifying discount for product {$productId}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la modifica dello sconto.',
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }
}