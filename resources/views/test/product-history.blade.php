<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Product History - {{ $product->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1B5E85;
            --light-bg: #EFF6FF;
            --text-dark: #333333;
        }
        
        body {
            background-color: #f5fdf1ff;
            color: var(--text-dark);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .card {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06) !important;
        }
        
        .card-header {
            background: var(--primary) !important;
            color: white !important;
            border-radius: 0 !important;
        }
        
        .nav-tabs {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }
        
        .nav-tabs .nav-link {
            color: var(--text-dark) !important;
            border: none;
            border-radius: 0;
            padding: 0.75rem 1rem;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link i,
        .nav-tabs .nav-link span {
            color: var(--text-dark) !important;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary) !important;
            border-bottom: 3px solid var(--primary);
            background-color: var(--light-bg);
        }
        
        .nav-tabs .nav-link:hover i,
        .nav-tabs .nav-link:hover span {
            color: var(--primary) !important;
        }
        
        .nav-tabs .nav-link.active {
            color: white !important;
            background-color: var(--primary);
            border: none;
            border-bottom: none;
        }
        
        .nav-tabs .nav-link.active i,
        .nav-tabs .nav-link.active span {
            color: white !important;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        
        .tab-content {
            background-color: #ffffff;
            padding: 1.5rem !important;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 0;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        
        table {
            font-size: 0.875rem;
        }
        
        .table th {
            background-color: var(--light-bg);
            color: var(--primary);
            font-weight: 600;
            border-color: #dee2e6;
        }
        
        .table td {
            border-color: #e9ecef;
            padding: 0.75rem;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge.bg-primary {
            background-color: var(--primary) !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4" style="background-color: #ffffff;">
        
        {{-- <!-- Debug Panel (Collapsible) -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#debugPanel">
                    <i class="bi bi-bug me-2"></i>Toggle Debug Info
                </button>
            </div>
            <div class="collapse" id="debugPanel">
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6><i class="bi bi-info-circle me-2"></i>Product Details:</h6>
                        <ul class="mb-0">
                            <li><strong>ID:</strong> {{ $product->id }}</li>
                            <li><strong>Code:</strong> {{ $product->code }}</li>
                            <li><strong>Name:</strong> {{ $product->name }}</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning mb-3">
                        <h6><i class="bi bi-database me-2"></i>Debug Counts:</h6>
                        <ul class="mb-0">
                            <li><strong>Raw Sale Items Count:</strong> {{ $rawCount ?? 0 }}</li>
                            <li><strong>Sales Items with Join:</strong> {{ count($salesItems ?? []) }}</li>
                            <li><strong>Final Sales History:</strong> {{ count($salesHistory ?? []) }}</li>
                        </ul>
                    </div>

                    @if(isset($rawSaleItems) && count($rawSaleItems) > 0)
                        <div class="alert alert-primary">
                            <h6><i class="bi bi-database me-2"></i>Raw Database Data:</h6>
                            <pre class="mb-0" style="max-height: 300px; overflow-y: auto;">{{ json_encode($rawSaleItems->toArray(), JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div> --}}

        <!-- Product History Modal Content (Standalone) -->
        <div class="card border-0 shadow-sm">
            <!-- Header -->
            <div class="card-header" style="background: #1B5E85;">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="bi bi-clock-history me-2"></i>
                    Product History - {{ $product->name }}
                </h5>
            </div>

            <!-- Body -->
            <div class="card-body p-0">
                <!-- Tabs -->
                <div class="border-bottom bg-white">
                    <ul class="nav nav-tabs border-0 px-3" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sales-tab" type="button">
                                <i class="bi bi-bag-check me-1"></i><span>Sales</span> <span class="badge bg-success ms-1">{{ count($salesHistory ?? []) }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#purchases-tab" type="button">
                                <i class="bi bi-cart-plus me-1"></i><span>Purchases</span> <span class="badge bg-success ms-1">{{ count($purchasesHistory ?? []) }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#returns-tab" type="button">
                                <i class="bi bi-arrow-return-left me-1"></i><span>Returns</span> <span class="badge bg-warning ms-1">{{ count($returnsHistory ?? []) }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#quotations-tab" type="button">
                                <i class="bi bi-quote me-1"></i><span>Quotations</span> <span class="badge bg-info ms-1">{{ count($quotationsHistory ?? []) }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-4" style="max-height: 500px; overflow-y: auto;">
                    <!-- Sales Tab -->
                    <div class="tab-pane fade show active" id="sales-tab">
                        @include('livewire.admin.partials.sales-history')
                    </div>

                    <!-- Purchases Tab -->
                    <div class="tab-pane fade" id="purchases-tab">
                        @include('livewire.admin.partials.purchases-history')
                    </div>

                    <!-- Returns Tab -->
                    <div class="tab-pane fade" id="returns-tab">
                        @include('livewire.admin.partials.returns-history')
                    </div>

                    <!-- Quotations Tab -->
                    <div class="tab-pane fade" id="quotations-tab">
                        @include('livewire.admin.partials.quotations-history')
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="card-footer border-0 bg-white">
                <button onclick="window.close()" class="btn rounded-0" style="background: #1B5E85; color: white;">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




