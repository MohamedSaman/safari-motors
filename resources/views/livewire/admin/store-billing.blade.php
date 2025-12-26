
<div class="container-fluid py-4" style="background: #f8f9fa; min-height: 100vh;">
    <!-- Opening Cash Modal -->
    @if($showOpeningCashModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.6);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-cash-coin me-2"></i> Start POS Session
                    </h5>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="bi bi-shop-window display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold mb-3">Opening Cash Amount</h5>
                    <p class="text-muted mb-4">Enter the cash in drawer to begin today's session</p>

                    <input type="number" class="form-control form-control-lg text-center mb-4 shadow-sm"
                           wire:model.live="openingCashAmount" min="0" step="0.01" autofocus
                           placeholder="0.00" style="font-size: 1.8rem; border: 2px solid #0d6efd;">
                    @error('openingCashAmount')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror

                    <button class="btn btn-primary btn-lg w-100 rounded-pill"
                            wire:click="submitOpeningCash">
                        <i class="bi bi-check2-circle me-2"></i> Start Session
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="row g-2">

        <!-- Header -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center py-3 px-4 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-shop fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-primary">SAFARI MOTORS</h4>
                            <small class="text-muted">Point of Sale System</small>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button class="btn btn-outline-primary rounded-pill px-4"
                                wire:click="viewCloseRegisterReport">
                            <i class="bi bi-receipt-cutoff me-2"></i> View Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Customer Information -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-person-circle me-2"></i> Customer
                    </h5>
                    <button class="btn btn-sm btn-outline-primary rounded-pill"
                            wire:click="openCustomerModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Customer
                    </button>
                </div>
                <div class="card-body px-4">
                    <select class="form-control form-select-lg shadow-sm" wire:model.live="customerId">
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->name }}
                            @if($customer->phone) - {{ $customer->phone }} @endif
                            @if($customer->name === 'Walking Customer') (Default) @endif
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Product Search -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-search me-2"></i> Add Products
                    </h5>
                </div>
                <div class="card-body px-4" style="position: relative;">
                    <input type="text" class="form-control form-control-lg shadow-sm mb-3"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by name, code, model...">

                    @if($search && count($searchResults) > 0)
                    <div class="border rounded-3 shadow-lg" style="position: absolute; top: 70px; left: 16px; right: 16px; max-height: 500px; overflow-y: scroll; background: white; z-index: 1000; border: 1px solid #dee2e6;">
                        @foreach($searchResults as $product)
                        <div class="p-3 border-bottom hover-bg-light d-flex justify-content-between align-items-center"
                             wire:click="addToCart({{ json_encode($product) }})"
                             style="cursor: pointer;">
                            <div>
                                <div class="fw-bold">{{ $product['name'] }}</div>
                                <div class="text-muted small">{{ $product['code'] }} • {{ $product['model'] }}</div>
                            </div>
                            <div class="text-end">
                                <div class="text-primary fw-bold">Rs.{{ number_format($product['price'], 2) }}</div>
                                <div class="text-success small">Stock: {{ $product['stock'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @elseif($search)
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-search display-4 opacity-50"></i>
                        <p>No products found</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <!-- Sale Items -->
            <div class="card border-0 shadow-sm rounded-4" style="min-height:55vh;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-cart4 me-2"></i> Sale Items
                    </h5>
                    <span class="badge bg-primary rounded-pill px-3 py-2">{{ count($cart) }} items</span>
                </div>

                <div class="card-body p-0">
                    @if(count($cart) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Disc./Unit</th>
                                    <th>Total</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $item['name'] }}</div>
                                        <div class="text-muted small">
                                            {{ $item['code'] }} • {{ $item['model'] }}
                                        </div>
                                        <div class="text-success small">Stock: {{ $item['stock'] }}</div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center"
                                               wire:model.live.debounce.500ms="cart.{{ $index }}.price"
                                               min="0" step="0.01">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button class="btn btn-sm btn-outline-secondary rounded-start px-3"
                                                    wire:click="decrementQuantity({{ $index }})">-</button>
                                            <input type="number" class="form-control form-control-sm text-center border-0 bg-white"
                                                   wire:model.live.debounce.500ms="cart.{{ $index }}.quantity"
                                                   min="1" max="{{ $item['stock'] }}" style="width: 70px;">
                                            <button class="btn btn-sm btn-outline-secondary rounded-end px-3"
                                                    wire:click="incrementQuantity({{ $index }})">+</button>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center"
                                               wire:change="updateDiscount({{ $index }}, $event.target.value)"
                                               min="0" step="0.01" value="{{ $item['discount'] }}">
                                    </td>
                                    <td class="fw-bold">Rs.{{ number_format($item['total'], 2) }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger rounded-circle"
                                                wire:click="removeFromCart({{ $index }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light border-top">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold pe-4">Subtotal</td>
                                    <td class="fw-bold">Rs.{{ number_format($subtotal, 2) }}</td>
                                    <td></td>
                                </tr>

                                <!-- Additional Discount -->
                                <tr>
                                    <td colspan="4" class="text-end fw-bold pe-4 align-middle">
                                        Additional Discount
                                        @if($additionalDiscount > 0)
                                        <button class="btn btn-sm btn-link text-danger p-0 ms-2"
                                                wire:click="removeAdditionalDiscount">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        @endif
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control text-center text-danger"
                                                   wire:model.live="additionalDiscount"
                                                   min="0" step="{{ $additionalDiscountType === 'percentage' ? '1' : '0.01' }}"
                                                   @if($additionalDiscountType === 'percentage') max="100" @endif>
                                            <button class="btn btn-outline-secondary dropdown-toggle"
                                                    type="button" wire:click="toggleDiscountType">
                                                {{ $additionalDiscountType === 'percentage' ? '%' : 'Rs.' }}
                                            </button>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-danger">
                                        @if($additionalDiscount > 0)
                                        - Rs.{{ number_format($additionalDiscountAmount, 2) }}
                                        @if($additionalDiscountType === 'percentage')
                                        <small>({{ $additionalDiscount }}%)</small>
                                        @endif
                                        @endif
                                    </td>
                                </tr>

                                <!-- Grand Total -->
                                <tr class="table-primary">
                                    <td colspan="5" class="text-end fw-bold fs-5 pe-4">Grand Total</td>
                                    <td class="fw-bold fs-5 text-primary">
                                        Rs.{{ number_format($grandTotal, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="card-footer bg-white border-0 d-flex justify-content-end p-3">
                        <button class="btn btn-outline-danger rounded-pill px-4"
                                wire:click="clearCart">
                            <i class="bi bi-trash3 me-2"></i> Clear All
                        </button>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-cart display-1 d-block mb-3 opacity-50"></i>
                        <p class="lead">Cart is empty</p>
                        <small>Search and add products from the right panel</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Payment Section -->
            <div class="card border-0 shadow-sm rounded-4" style="min-height:55vh;">
                <div class="card-header bg-white py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-credit-card-2-front me-2"></i> Payment
                    </h5>
                </div>
                <div class="card-body px-4">
                    <label class="form-label fw-semibold mb-2">Payment Method</label>
                    <select class="form-select form-select-lg shadow-sm mb-4"
                            wire:model.live="paymentMethod">
                        <option value="cash">Cash</option>
                        <option value="credit">Credit (Pay Later)</option>
                    </select>

                    @if($paymentMethod === 'cash')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cash Received</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rs.</span>
                            <input type="number" class="form-control"
                                   wire:model.live="cashAmount" min="0" step="0.01">
                        </div>
                    </div>
                    @endif

                    @if($paymentMethod === 'cheque')
                    <!-- Cheque fields - add your existing cheque input logic here -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i> Cheque details will be added below
                    </div>
                    <!-- Add your cheque form here if needed -->
                    @endif

                    @if($paymentMethod === 'bank_transfer')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Transfer Amount</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rs.</span>
                            <input type="number" class="form-control"
                                   wire:model.live="bankTransferAmount" min="0" step="0.01">
                        </div>
                    </div>
                    @endif

                    @if($paymentMethod === 'credit')
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Credit Sale</strong><br>
                        Full amount Rs.{{ number_format($grandTotal, 2) }} will be marked as due.
                    </div>
                    @endif

                    <!-- Payment Summary -->
                    @if($grandTotal > 0)
                    <div class="bg-light rounded-3 p-3 mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Grand Total:</span>
                            <span class="fw-bold">Rs.{{ number_format($grandTotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Paid:</span>
                            <span class="text-success fw-bold">Rs.{{ number_format($totalPaidAmount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mt-2">
                            <span>Due:</span>
                            <span class="{{ $dueAmount > 0 ? 'text-danger' : 'text-success' }}">
                                Rs.{{ number_format($dueAmount, 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white border-0 p-4">
                    <button class="btn btn-success btn-lg w-100 rounded-pill shadow-lg"
                            wire:click="processSale" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check2-circle me-2"></i> Complete Sale
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2"></span> Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>

    </div>


    {{-- Add Customer Modal --}}
    @if($showCustomerModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0">
                <div class="modal-header text-white rounded-0" style="background: #2563EB;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-plus me-2"></i>Add New Customer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeCustomerModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color:#2563EB;">Name *</label>
                            <input type="text" class="form-control rounded-0" wire:model="customerName" placeholder="Enter customer name">
                            @error('customerName') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color:#2563EB;">Phone *</label>
                            <input type="text" class="form-control rounded-0" wire:model="customerPhone" placeholder="Enter phone number">
                            @error('customerPhone') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color:#2563EB;">Email</label>
                            <input type="email" class="form-control rounded-0" wire:model="customerEmail" placeholder="Enter email address">
                            @error('customerEmail') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color:#2563EB;">Customer Type *</label>
                            <select class="form-select rounded-0" wire:model="customerType">
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>

                            </select>
                            @error('customerType') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color:#2563EB;">Business Name</label>
                            <input type="text" class="form-control rounded-0" wire:model="businessName" placeholder="Enter business name">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="color:#2563EB;">Address *</label>
                            <textarea class="form-control rounded-0" wire:model="customerAddress" rows="3" placeholder="Enter address"></textarea>
                            @error('customerAddress') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <button type="button" class="btn btn-secondary rounded-0" style="background: #2563EB;" wire:click="closeCustomerModal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn rounded-0 text-white" style="background: #2563EB; border-color:#2563EB;" wire:click="createCustomer">
                        <i class="bi bi-check-circle me-2"></i>Create Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Payment Confirmation Modal --}}
    @if($showPaymentConfirmModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.7);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header text-white rounded-0" style="background: #2563EB;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle me-2"></i>Partial Payment Confirmation
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3 rounded-0">
                        <h6 class="alert-heading">Payment Amount Less Than Grand Total</h6>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Grand Total:</strong>
                            <span>Rs.{{ number_format($grandTotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Paid Amount:</strong>
                            <span class="text-info">Rs.{{ number_format($totalPaidAmount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Due Amount:</strong>
                            <span class="text-danger">Rs.{{ number_format($pendingDueAmount, 2) }}</span>
                        </div>
                    </div>
                    <p class="mb-0">
                        The due amount of <strong class="text-danger">Rs.{{ number_format($pendingDueAmount, 2) }}</strong>
                        will be added to the customer's account. Do you want to proceed?
                    </p>
                </div>
                <div class="modal-footer rounded-0">
                    <button type="button" class="btn btn-secondary rounded-0" style="background: #2563EB;" wire:click="cancelSaleConfirmation">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn rounded-0 text-white" style="background: #2563EB; border-color:#2563EB;" wire:click="confirmSaleWithDue">
                        <i class="bi bi-check-circle me-2"></i>Yes, Proceed with Due
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Sale Preview Modal --}}
    @if($showSaleModal && $createdSale)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-0">
                <div class="modal-header text-white rounded-0" style="background: #2563EB;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-cart-check me-2"></i>
                        Sale Completed Successfully! - {{ $createdSale->invoice_number }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="createNewSale"></button>
                </div>

                <div class="modal-body p-0">
                    <div class="sale-preview p-4" id="saleReceiptPrintContent">
                        {{-- Screen Only Header (visible on screen, hidden on print) --}}
                        <div class="screen-only-header mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                {{-- Left: Logo --}}
                                <div style="flex: 0 0 150px;">
                                    <img src="{{ asset('images') }}" alt="Logo" class="img-fluid" style="max-height:80px;">
                                </div>

                                {{-- Center: Company Name --}}
                                <div class="text-center" style="flex: 1;">
                                    <h2 class="mb-0 fw-bold" style="font-size: 2.5rem; letter-spacing: 2px;">SAFARI MOTORS</h2>
                                    <p class="mb-0 text-muted small">IMPORTERS & DISTRIBUTERS OF MAHINDRA AND TATA PARTS</p>
                                </div>

                                {{-- Right: Motor Parts & Invoice --}}
                                <div class="text-end" style="flex: 0 0 150px;">
                                    <h5 class="mb-0 fw-bold">MOTOR PARTS</h5>
                                    <h6 class="mb-0 text-muted">INVOICE</h6>
                                </div>
                            </div>
                            <hr class="my-2" style="border-top: 2px solid #000;">
                        </div>

                        {{-- Customer & Sale Details Side by Side --}}
                        <div class="row mb-3 invoice-info-row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Customer :</strong></p>
                                <p class="mb-0">{{ $createdSale->customer->name }}</p>
                                <p class="mb-0">{{ $createdSale->customer->address }}</p>
                                <p class="mb-0"><strong>Tel:</strong> {{ $createdSale->customer->phone }}</p>
                            </div>
                            <div class="col-6 text">
                                <table class="table-borderless ms-auto" style="width: auto; display: inline-table;">
                                    <tr>
                                        <td class="pe-3"><strong>Invoice #</strong></td>
                                        <td>{{ $createdSale->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3"><strong>Sale ID</strong></td>
                                        <td>{{ $createdSale->sale_id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3"><strong>Date</strong></td>
                                        <td>{{ $createdSale->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3"><strong>Time</strong></td>
                                        <td>{{ $createdSale->created_at->format('H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered invoice-table">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th>ITEM CODE</th>
                                        <th>DESCRIPTION</th>
                                        <th width="80" class="text-center">QTY</th>
                                        <th width="120" class="text-end">UNIT PRICE</th>
                                        <th width="120" class="text-end">UNIT DISCOUNT</th>
                                        <th width="120" class="text-end">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($createdSale->items as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->product_code }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rs.{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end">
                                            @if($item->discount_per_unit > 0)
                                                - Rs.{{ number_format($item->discount_per_unit, 2) }}
                                            @else
                                                - Rs.0.00
                                            @endif
                                        </td>
                                        <td class="text-end">Rs.{{ number_format(($item->unit_price - $item->discount_per_unit) * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="totals-row">
                                        <td colspan="6" class="text-end"><strong>Subtotal</strong></td>
                                        <td class="text-end"><strong>Rs.{{ number_format($createdSale->subtotal, 2) }}</strong></td>
                                    </tr>
                                    @if($createdSale->discount_amount > 0)
                                    <tr class="totals-row">
                                        <td colspan="6" class="text-end"><strong>Discount</strong></td>
                                        <td class="text-end"><strong>-Rs.{{ number_format($createdSale->discount_amount, 2) }}</strong></td>
                                    </tr>
                                    @endif
                                    <tr class="totals-row grand-total">
                                        <td colspan="6" class="text-end"><strong>Grand Total</strong></td>
                                        <td class="text-end"><strong>Rs.{{ number_format($createdSale->total_amount, 2) }}</strong></td>
                                    </tr>
                                    @if($createdSale->payments->count() > 0)
                                    <tr class="totals-row">
                                        <td colspan="6" class="text-end"><strong>Paid Amount</strong></td>
                                        <td class="text-end"><strong>Rs.{{ number_format($createdSale->payments->sum('amount'), 2) }}</strong></td>
                                    </tr>
                                    @endif
                                    @if($createdSale->due_amount > 0)
                                    <tr class="totals-row">
                                        <td colspan="6" class="text-end"><strong>Due Amount</strong></td>
                                        <td class="text-end"><strong>Rs.{{ number_format($createdSale->due_amount, 2) }}</strong></td>
                                    </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>

                        {{-- Footer Note --}}
                        <div class="invoice-footer mt-4">
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <p class=""><strong>.............................</strong></p>
                                    <p class="mb-2"><strong>Checked By</strong></p>
                                    <img src="{{ asset('images') }}" alt="TATA" style="height: 35px;margin: auto;">
                                </div>
                                <div class="col-4">
                                    <p class=""><strong>.............................</strong></p>
                                    <p class="mb-2"><strong>Authorized Officer</strong></p>
                                    <img src="{{ asset('images') }}" alt="SAFARI" style="height: 35px;margin: auto;">
                                </div>
                                <div class="col-4">
                                    <p class=""><strong>.............................</strong></p>
                                    <p class="mb-2"><strong>Customer Stamp</strong></p>
                                    <img src="{{ asset('images') }}" alt="Mahindra" style="height: 35px;margin: auto;">
                                </div>
                            </div>
                            <div class="border-top pt-3">
                                <p class="text-center"><strong>ADDRESS :</strong> Sample Address</p>
                                <p class="text-center"><strong>TEL :</strong> (076) 1234567, <strong>EMAIL :</strong> sample@gmail.com</p>
                                <p class="text-center mt-2" style="font-size: 11px;"><strong>Goods return will be accepted within 10 days only. Electrical and body parts non-returnable.</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Buttons --}}
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary me-2" wire:click="createNewSale">
                        <i class="bi bi-x-circle me-2"></i>Close
                    </button>
                    <button type="button" class="btn btn-outline-primary me-2" wire:click="printSaleReceipt">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>
                    <button type="button" class="btn btn-success" wire:click="downloadInvoice">
                        <i class="bi bi-download me-2"></i>Download Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Close Register Modal --}}
    @if($showCloseRegisterModal)
    <div class="modal fade show d-block" id="closeRegisterModal" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #2563EB; color: white;">
                    <h5 class="modal-title fw-bold" id="closeRegisterModalLabel">
                        <i class="bi bi-x-circle me-2"></i>CLOSE REGISTER ({{ date('d/m/Y H:i') }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="cancelCloseRegister" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="closeRegisterPrintContent">
                    {{-- Print Header (hidden on screen, shown on print) --}}
                    <div class="print-header text-center mb-4">
                        <div class="w-100 d-flex justify-content-center">
                            <img src="{{ asset('images/SAFARI.png') }}" alt="Logo" class="img-fluid" style="max-height:100px;">
                        </div>
                        <p>Sample Address</p>
                        <p><strong>TEL:</strong> (076) 1234567 | <strong>EMAIL:</strong> sample@gmail.com</p>

                    </div>

                    <p class="text-muted mb-3 no-print">Please review the details below as <strong>paid (total)</strong></p>

                    {{-- Summary Table --}}
                    <table class="table table-sm print-table">
                        <tbody>
                            <tr>
                                <td>Cash in hand:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['opening_cash'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Cash Sales (POS):</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['pos_cash_sales'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Cheque Payment (POS):</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['pos_cheque_payment'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Bank / Online Transfer (POS):</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['pos_bank_transfer'] ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-warning">
                                <td class="fw-semibold">Admin Payments - Total:</td>
                                <td class="text-end fw-semibold">Rs.{{ number_format($sessionSummary['total_admin_payment'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4">└ Cash:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['total_admin_cash_payment'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4">└ Cheque:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['total_admin_cheque_payment'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4">└ Bank Transfer:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['total_admin_bank_transfer'] ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-info">
                                <td class="fw-semibold">Total Cash Amount (POS + Admin):</td>
                                <td class="text-end fw-semibold">Rs.{{ number_format($sessionSummary['total_cash_from_sales'] ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-semibold">Total POS Sales:</td>
                                <td class="text-end fw-bold">Rs.{{ number_format($sessionSummary['total_pos_sales'] ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-semibold">Total Admin Sales:</td>
                                <td class="text-end fw-bold">Rs.{{ number_format($sessionSummary['total_admin_sales'] ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td class="fw-semibold">Total Cash Payment Today:</td>
                                <td class="text-end fw-bold">Rs.{{ number_format($sessionSummary['total_cash_payment_today'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Expenses:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['expenses'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Refunds:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['refunds'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Cash Deposit - Bank:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['cash_deposit_bank'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Supplier Payments:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['supplier_payment'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Supplier Cash Payments:</td>
                                <td class="text-end">Rs.{{ number_format($sessionSummary['supplier_cash_payment'] ?? 0, 2) }}</td>
                            </tr>

                            <tr class="table-success">
                                <td class="fw-bold">Total Cash in Hand:</td>
                                <td class="text-end fw-bold">Rs.{{ number_format($sessionSummary['expected_cash'] ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                    {{-- Notes --}}
                    <div class="mb-3">
                        <label class="form-label"><strong>Note:</strong></label>
                        <textarea class="form-control" rows="2" wire:model="closeRegisterNotes" placeholder="Add any notes...">{{ $closeRegisterNotes ?? '' }}</textarea>
                    </div>

                    {{-- Cash Difference Alert --}}
                    @if($closeRegisterCash > 0)
                    @php
                    $difference = $closeRegisterCash - ($sessionSummary['expected_cash'] ?? 0);
                    @endphp
                    @if($difference != 0)
                    <div class="alert alert-{{ $difference > 0 ? 'warning' : 'danger' }}">
                        <strong>Cash Difference:</strong> Rs.{{ number_format(abs($difference), 2) }} ({{ $difference > 0 ? 'Excess' : 'Short' }})
                    </div>
                    @else

                    @endif
                    @endif

                    {{-- Print Footer (hidden on screen) --}}
                    <div class="register-print-footer">
                        <p><strong>Date:</strong> {{ date('d/m/Y') }} | <strong>Time:</strong> {{ date('H:i') }}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        wire:click="closeRegisterAndRedirect"
                        wire:loading.attr="disabled"
                        wire:loading.class="disabled">
                        <span wire:loading.remove wire:target="closeRegisterAndRedirect">
                            <i class="bi bi-x-circle me-1"></i>Close Register
                        </span>
                        <span wire:loading wire:target="closeRegisterAndRedirect">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Closing Register...
                        </span>
                    </button>
                    <button type="button" class="btn btn-info" wire:click="downloadCloseRegisterReport">
                        <i class="bi bi-download me-1"></i>Download
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
.hover-bg-light:hover {
        background-color: #f1f5f9 !important;
        transition: background 0.2s;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .rounded-4 {
        border-radius: 1.25rem !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
    .container-fluid {
        background-color: #f5fdf1ff !important;
    }

    .header-section {
        border-bottom: 1px solid #e9ecef;
    }

    .company-logo {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Hide print headers on screen */
    .print-only-header,
    .print-header,
    .print-footer,
    .register-print-footer {
        display: none;
    }

    .form-control,
    .form-select,
    .input-group-text,
    .btn {
        border-radius: 0 !important;
    }

    .search-results {
        max-height: 400px;
        overflow-y: auto;
        border: 2px solid #2563EB !important;
        border-radius: 0;
        position: relative;
        z-index: 10;
        background-color: white;
    }

    .search-results::-webkit-scrollbar {
        width: 8px;
    }

    .search-results::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .search-results::-webkit-scrollbar-thumb {
        background: #2563EB;
        border-radius: 4px;
    }

    .search-results::-webkit-scrollbar-thumb:hover {
        background: #2563EB;
    }

    .search-item:last-child {
        border-bottom: none !important;
    }

    /* Print styles for sale receipt */
    @media print {
        body * {
            visibility: hidden;
        }

        #saleReceiptPrintContent,
        #saleReceiptPrintContent * {
            visibility: visible !important;
        }

        #saleReceiptPrintContent {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 15mm !important;
            background: white !important;
            color: black !important;
        }

        /* Hide screen elements */
        .modal-header,
        .modal-footer,
        .btn,
        .badge,
        .screen-only-header {
            display: none !important;
            visibility: hidden !important;
        }

        /* Show print header */
        .print-only-header {
            display: block !important;
            visibility: visible !important;
            text-align: center !important;
            margin-bottom: 20px !important;
            border-bottom: 2px solid #000 !important;
            padding-bottom: 10px !important;
        }

        /* Invoice table styles */
        .invoice-table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 15px 0 !important;
            font-size: 12px !important;
        }

        .invoice-table th {
            background-color: #ffffff !important;
            border: 1px solid #000 !important;
            padding: 8px !important;
            font-weight: bold !important;
            text-align: center !important;
        }

        .invoice-table td {
            border: 1px solid #000 !important;
            padding: 6px 8px !important;
        }

        .invoice-table .totals-row td {
            border-top: 2px solid #000 !important;
            font-weight: bold !important;
            padding: 8px !important;
        }

        .invoice-table .grand-total td {
            border-top: 3px double #000 !important;
            font-size: 14px !important;
            background-color: #f0f0f0 !important;
        }

        /* Page setup */
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
    }

    @media screen {
        .print-only-header {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-close alerts after 5 seconds
    document.addEventListener('livewire:initialized', () => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Listen for modal show event
        Livewire.on('showModal', (event) => {
            const modalId = Array.isArray(event) ? event[0] : event;
            console.log('Show modal event received:', modalId);

            setTimeout(() => {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    const existingModal = bootstrap.Modal.getInstance(modalElement);
                    if (existingModal) {
                        existingModal.dispose();
                    }
                    const modal = new bootstrap.Modal(modalElement, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    modal.show();
                }
            }, 200);
        });
    });

    // Auto-hide success alert after 3 seconds
    setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);

    // Prevent form submission on enter key in search
    document.addEventListener('keydown', function(e) {
        if (e.target.type === 'text' && e.target.getAttribute('wire:model') === 'search') {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        }
    });

    // Handle modal cleanup when hidden
    document.addEventListener('DOMContentLoaded', function() {
        const closeRegisterModal = document.getElementById('closeRegisterModal');
        if (closeRegisterModal) {
            closeRegisterModal.addEventListener('hidden.bs.modal', function() {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
        }

        // Watch for sale modal changes
        Livewire.on('saleSaved', function() {
            setTimeout(() => {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }, 100);
        });
    });
</script>
@endpush





