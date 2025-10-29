<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerInquiry;
use App\Models\CustomerEmailAlias;
use App\Services\CustomerService;
use App\Services\CsvExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService,
        protected CsvExportService $csvExportService
    ) {
    }

    /**
     * Display a listing of customers.
     */
    public function index(Request $request): Response
    {
        $query = Customer::with(['inquiries' => function($q) {
            $q->latest()->limit(1);
        }]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('primary_email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by GDPR consent
        if ($request->has('gdpr_filter')) {
            if ($request->gdpr_filter === 'with') {
                $query->where('gdpr_consent', true);
            } elseif ($request->gdpr_filter === 'without') {
                $query->where('gdpr_consent', false);
            }
        }

        // Order by last contact date (most recent first)
        $query->orderBy('last_contact_at', 'desc')
              ->orderBy('created_at', 'desc');

        $customers = $query->get()->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'primary_email' => $customer->primary_email,
                'phone' => $customer->phone,
                'total_inquiries' => $customer->total_inquiries,
                'last_contact_at' => $customer->last_contact_at?->format('d/m/Y H:i'),
                'gdpr_consent' => $customer->gdpr_consent,
                'gdpr_consent_date' => $customer->gdpr_consent_date?->format('d/m/Y H:i'),
                'has_recent_inquiry' => $customer->inquiries->isNotEmpty(),
            ];
        });

        return Inertia::render('Admin/Customers/Index', [
            'customers' => $customers,
            'filters' => [
                'search' => $request->search,
                'gdpr_filter' => $request->gdpr_filter,
            ],
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Customers/Create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'primary_email' => 'required|email|max:255|unique:customers,primary_email',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:5000',
            'gdpr_consent' => 'sometimes|boolean',
            'gdpr_consent_date' => 'nullable|date',
        ]);

        // If GDPR consent is true, set the date if not provided
        if (($validated['gdpr_consent'] ?? false) && !isset($validated['gdpr_consent_date'])) {
            $validated['gdpr_consent_date'] = now();
        }

        $customer = Customer::create($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Cliente creato con successo!');
    }

    /**
     * Display the specified customer with timeline.
     */
    public function show(Request $request, Customer $customer): Response|JsonResponse
    {
        $customer->load(['inquiries.product', 'emailAliases']);

        $inquiries = $customer->inquiries->map(function ($inquiry) {
            return [
                'id' => $inquiry->id,
                'email_used' => $inquiry->email_used,
                'name_used' => $inquiry->name_used,
                'phone' => $inquiry->phone,
                'product_id' => $inquiry->product_id,
                'product_title' => $inquiry->product_title,
                'product_cover_image' => $inquiry->product?->cover_image_url,
                'message' => $inquiry->message,
                'admin_notes' => $inquiry->admin_notes,
                'responded_at' => $inquiry->responded_at?->format('d/m/Y H:i'),
                'is_responded' => $inquiry->isResponded(),
                'created_at' => $inquiry->created_at->format('d/m/Y H:i'),
                'created_at_full' => $inquiry->created_at->toIso8601String(),
            ];
        });

        $customerData = [
            'id' => $customer->id,
            'name' => $customer->name,
            'primary_email' => $customer->primary_email,
            'phone' => $customer->phone,
            'notes' => $customer->notes,
            'gdpr_consent' => $customer->gdpr_consent,
            'gdpr_consent_date' => $customer->gdpr_consent_date?->format('d/m/Y H:i'),
            'total_inquiries' => $customer->total_inquiries,
            'last_contact_at' => $customer->last_contact_at?->format('d/m/Y H:i'),
            'created_at' => $customer->created_at->format('d/m/Y H:i'),
            'email_aliases' => $customer->emailAliases->pluck('email')->toArray(),
        ];

        // Return JSON if requested (for merge modal)
        if ($request->wantsJson()) {
            return response()->json([
                'customer' => $customerData,
                'inquiries' => $inquiries,
            ]);
        }

        return Inertia::render('Admin/Customers/Show', [
            'customer' => $customerData,
            'inquiries' => $inquiries,
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): Response
    {
        return Inertia::render('Admin/Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'primary_email' => $customer->primary_email,
                'phone' => $customer->phone,
                'notes' => $customer->notes,
                'gdpr_consent' => $customer->gdpr_consent,
                'gdpr_consent_date' => $customer->gdpr_consent_date?->format('Y-m-d\TH:i'),
            ],
        ]);
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'primary_email' => 'required|email|max:255|unique:customers,primary_email,' . $customer->id,
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:5000',
            'gdpr_consent' => 'sometimes|boolean',
            'gdpr_consent_date' => 'nullable|date',
        ], [
            'name.required' => 'Il nome è obbligatorio.',
            'primary_email.required' => 'L\'email è obbligatoria.',
            'primary_email.email' => 'L\'email deve essere valida.',
            'primary_email.unique' => 'Questa email è già stata utilizzata.',
        ]);

        // If GDPR consent is being set to true, set the date if not provided
        if (($validated['gdpr_consent'] ?? false) && !isset($validated['gdpr_consent_date'])) {
            $validated['gdpr_consent_date'] = now();
        }

        // If GDPR consent is being set to false, clear the date
        if (isset($validated['gdpr_consent']) && !$validated['gdpr_consent']) {
            $validated['gdpr_consent_date'] = null;
        }

        $customer->update($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Cliente aggiornato con successo!');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        // Customer inquiries will be deleted via cascade
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente eliminato con successo!');
    }

    /**
     * Show merge page with selected customers.
     */
    public function showMerge(Request $request)
    {
        $customerIds = $request->validate([
            'customers' => 'required|array',
            'customers.*' => 'exists:customers,id',
        ])['customers'];

        if (count($customerIds) < 2) {
            return redirect()->route('customers.index')
                ->with('error', 'Seleziona almeno 2 clienti da unire.');
        }

        $customers = Customer::whereIn('id', $customerIds)
            ->with(['inquiries', 'emailAliases'])
            ->get();

        return Inertia::render('Admin/Customers/Merge', [
            'customers' => $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'primary_email' => $customer->primary_email,
                    'phone' => $customer->phone,
                    'notes' => $customer->notes,
                    'gdpr_consent' => $customer->gdpr_consent,
                    'gdpr_consent_date' => $customer->gdpr_consent_date?->format('Y-m-d\TH:i'),
                    'total_inquiries' => $customer->total_inquiries,
                    'last_contact_at' => $customer->last_contact_at?->format('d/m/Y H:i'),
                    'created_at' => $customer->created_at->format('d/m/Y H:i'),
                    'email_aliases' => $customer->emailAliases->pluck('email')->toArray(),
                ];
            }),
        ]);
    }

    /**
     * Merge customers.
     */
    public function merge(Request $request)
    {
        try {
            Log::info('=== MERGE START ===');
            
                $validated = $request->validate([
                'primary_customer_id' => 'required|exists:customers,id',
                'secondary_customer_ids' => 'required|array',
                'secondary_customer_ids.*' => 'exists:customers,id',
                'final_data' => 'required|array',
                'final_data.name' => 'required|string|max:255',
                'final_data.primary_email' => 'required|email|max:255',
                'final_data.phone' => 'nullable|string|max:50',
                'final_data.gdpr_consent' => 'sometimes|boolean',
                'final_data.gdpr_consent_date' => 'nullable|date',
            ]);
            
            Log::info('Request data:', $request->all());
            Log::info('Validated:', $validated);

            // Get all customer IDs to merge
            $primaryId = (int) $validated['primary_customer_id'];
            $secondaryIds = array_map('intval', $validated['secondary_customer_ids']);
            $allIds = array_unique(array_merge([$primaryId], $secondaryIds));
            
            Log::info('All IDs to merge:', $allIds);
            
            // Get all customers
            $customers = Customer::whereIn('id', $allIds)->get();
            Log::info('Found customers:', $customers->pluck('id')->toArray());
            
            if ($customers->count() !== count($allIds)) {
                throw new \Exception('Some customers not found');
            }
            
            // Get final data
            $finalData = $validated['final_data'];
            $finalEmail = strtolower(trim($finalData['primary_email']));
            
            // Collect all inquiries
            $inquiryCount = CustomerInquiry::whereIn('customer_id', $allIds)->count();
            $lastContact = CustomerInquiry::whereIn('customer_id', $allIds)->max('created_at');
            
            Log::info("Inquiries: {$inquiryCount}, Last contact: {$lastContact}");
            
            // Collect unique emails for aliases (excluding final email)
            $emailsToAlias = [];
            foreach ($customers as $customer) {
                $email = strtolower(trim($customer->primary_email));
                if ($email !== $finalEmail) {
                    $emailsToAlias[$email] = $customer->primary_email;
                }
            }
            
            // Get existing aliases
            $existingAliases = CustomerEmailAlias::whereIn('customer_id', $allIds)->get();
            foreach ($existingAliases as $alias) {
                $email = strtolower(trim($alias->email));
                if ($email !== $finalEmail && !isset($emailsToAlias[$email])) {
                    $emailsToAlias[$email] = $alias->email;
                }
            }
            
            Log::info('Emails to alias:', array_values($emailsToAlias));
            
            // Start database transaction
            DB::beginTransaction();
            
            try {
                // First, temporarily rename old customer emails to avoid conflicts
                foreach ($customers as $customer) {
                    if (strtolower($customer->primary_email) === $finalEmail) {
                        $customer->primary_email = 'temp_' . $customer->id . '_' . $customer->primary_email;
                        $customer->save();
                        Log::info("Temporarily renamed customer {$customer->id} email");
                    }
                }
                
                // Delete old aliases
                CustomerEmailAlias::whereIn('customer_id', $allIds)->delete();
                Log::info('Deleted old aliases');
                
                // Create merged customer
                $mergedCustomer = new Customer();
                $mergedCustomer->primary_email = $finalData['primary_email'];
                $mergedCustomer->name = $finalData['name'];
                $mergedCustomer->phone = !empty($finalData['phone']) ? $finalData['phone'] : null;
                $mergedCustomer->notes = null; // Don't merge notes
                $mergedCustomer->gdpr_consent = $finalData['gdpr_consent'] ?? false;
                $mergedCustomer->gdpr_consent_date = !empty($finalData['gdpr_consent_date']) ? $finalData['gdpr_consent_date'] : null;
                $mergedCustomer->total_inquiries = $inquiryCount;
                $mergedCustomer->last_contact_at = $lastContact;
                $mergedCustomer->save();
                
                Log::info('Created merged customer ID: ' . $mergedCustomer->id);
                
                // Transfer inquiries
                CustomerInquiry::whereIn('customer_id', $allIds)
                    ->update(['customer_id' => $mergedCustomer->id]);
                Log::info('Transferred inquiries');
                
                // Create new aliases
                foreach ($emailsToAlias as $email) {
                    $email = trim($email);
                    if (!empty($email) && strtolower($email) !== strtolower($mergedCustomer->primary_email)) {
                        try {
                            $alias = new CustomerEmailAlias();
                            $alias->customer_id = $mergedCustomer->id;
                            $alias->email = $email;
                            $alias->save();
                            Log::info("Created alias: {$email}");
                        } catch (\Exception $e) {
                            Log::warning("Failed to create alias {$email}: " . $e->getMessage());
                        }
                    }
                }
                
                // Delete old customers
                Customer::whereIn('id', $allIds)->delete();
                Log::info('Deleted old customers');
                
                DB::commit();
                Log::info('=== MERGE SUCCESS ===');
                Log::info('Merged customer ID: ' . $mergedCustomer->id);
                Log::info('Total customers after merge: ' . Customer::count());
                
                // Verify old customers are deleted
                $deletedCheck = Customer::whereIn('id', $allIds)->count();
                Log::info('Old customers still exist: ' . $deletedCheck);
                
                return redirect()->route('customers.show', $mergedCustomer)
                    ->with('success', 'Clienti uniti con successo!');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Transaction failed: ' . $e->getMessage());
                throw $e;
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed');
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('=== MERGE ERROR ===');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            
            return back()
                ->with('error', 'Errore: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Export customers to CSV.
     */
    public function export(Request $request)
    {
        $query = Customer::query();

        // Apply same filters as index
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('primary_email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('gdpr_filter')) {
            if ($request->gdpr_filter === 'with') {
                $query->where('gdpr_consent', true);
            } elseif ($request->gdpr_filter === 'without') {
                $query->where('gdpr_consent', false);
            }
        }

        $customers = $query->orderBy('last_contact_at', 'desc')->get();

        return $this->csvExportService->exportCustomers($customers);
    }

    /**
     * Update inquiry admin notes or mark as responded.
     */
    public function updateInquiry(Request $request, CustomerInquiry $inquiry)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:5000',
            'mark_as_responded' => 'sometimes|boolean',
        ]);

        if (isset($validated['admin_notes'])) {
            $inquiry->admin_notes = $validated['admin_notes'];
        }

        if ($validated['mark_as_responded'] ?? false) {
            $inquiry->markAsResponded();
        } else {
            $inquiry->save();
        }

        return back()->with('success', 'Richiesta aggiornata con successo!');
    }
}
