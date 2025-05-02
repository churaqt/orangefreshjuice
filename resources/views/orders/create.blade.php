@extends('layouts.app')

@section('content')
<h1 class="page-title">Tambah Pesanan</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
            @csrf
            
            <div class="mb-4">
                <label for="customer_name" class="form-label">Nama Pelanggan</label>
                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                @error('customer_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <h5 class="mb-3">Pilih Produk</h5>
            
            <div id="productContainer">
                <div class="product-item card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Tipe Juice</label>
                                <select class="form-select juice-type-select" name="products[0][juice_type]" required>
                                    <option value="">Pilih Tipe Juice</option>
                                    <option value="fresh">Fresh Juice (Rp 18.000)</option>
                                    <option value="mix">Mix Juice (Rp 20.000)</option>
                                    <option value="berry">Berry Series (Rp 20.000)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Buah Pertama</label>
                                <select class="form-select product-select" name="products[0][id]" required>
                                    <option value="">Pilih Buah atau Sayur</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3 second-fruit-container" style="display: none;">
                                <label class="form-label">Buah Kedua</label>
                                <select class="form-select second-product-select" name="products[0][second_fruit_id]">
                                    <option value="">Pilih Buah Kedua</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="products[0][quantity]" value="1" min="1" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Level Gula</label>
                                <select class="form-select" name="products[0][sugar_level]" required>
                                    <option value="Normal">Normal</option>
                                    <option value="Less Sugar">Less Sugar</option>
                                    <option value="No Sugar">No Sugar</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Level Es</label>
                                <select class="form-select" name="products[0][ice_level]" required>
                                    <option value="Normal">Normal</option>
                                    <option value="Less Ice">Less Ice</option>
                                    <option value="No Ice">No Ice</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-danger remove-product" style="display: none;">Hapus</button>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <button type="button" class="btn btn-secondary" id="addProduct">Tambah Produk</button>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Proses Pesanan</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productCount = 1;
        
        // Function to toggle second fruit field based on juice type
        function toggleSecondFruitField() {
            document.querySelectorAll('.juice-type-select').forEach(select => {
                const productItem = select.closest('.product-item');
                const secondFruitContainer = productItem.querySelector('.second-fruit-container');
                const secondFruitSelect = productItem.querySelector('.second-product-select');
                
                if (select.value === 'mix' || select.value === 'berry') {
                    secondFruitContainer.style.display = 'block';
                    secondFruitSelect.required = true;
                } else {
                    secondFruitContainer.style.display = 'none';
                    secondFruitSelect.required = false;
                    secondFruitSelect.value = '';
                }
            });
        }
        
        // Add event listeners to juice type selects
        function addJuiceTypeListeners() {
            document.querySelectorAll('.juice-type-select').forEach(select => {
                select.addEventListener('change', toggleSecondFruitField);
            });
        }
        
        // Initialize
        addJuiceTypeListeners();
        
        // Add product
        document.getElementById('addProduct').addEventListener('click', function() {
            const productContainer = document.getElementById('productContainer');
            const productTemplate = document.querySelector('.product-item').cloneNode(true);
            
            // Update input names
            const inputs = productTemplate.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace('[0]', `[${productCount}]`));
                }
            });
            
            // Reset values
            const quantityInput = productTemplate.querySelector('input[name^="products"][name$="[quantity]"]');
            if (quantityInput) {
                quantityInput.value = 1;
            }
            
            const juiceTypeSelect = productTemplate.querySelector('.juice-type-select');
            if (juiceTypeSelect) {
                juiceTypeSelect.value = '';
            }
            
            const productSelect = productTemplate.querySelector('.product-select');
            if (productSelect) {
                productSelect.value = '';
            }
            
            const secondProductSelect = productTemplate.querySelector('.second-product-select');
            if (secondProductSelect) {
                secondProductSelect.value = '';
            }
            
            const secondFruitContainer = productTemplate.querySelector('.second-fruit-container');
            if (secondFruitContainer) {
                secondFruitContainer.style.display = 'none';
            }
            
            // Show remove button
            const removeButton = productTemplate.querySelector('.remove-product');
            if (removeButton) {
                removeButton.style.display = 'block';
            }
            
            productContainer.appendChild(productTemplate);
            productCount++;
            
            // Add event listeners to the new elements
            addJuiceTypeListeners();
            addRemoveEventListener();
        });
        
        // Function to add event listeners to remove buttons
        function addRemoveEventListener() {
            const removeButtons = document.querySelectorAll('.remove-product');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (document.querySelectorAll('.product-item').length > 1) {
                        this.closest('.product-item').remove();
                    }
                });
            });
        }
        
        // Initialize
        addRemoveEventListener();
    });
</script>
@endsection