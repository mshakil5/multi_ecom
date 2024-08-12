@extends('admin.layouts.admin')

@section('content')

<section class="content pt-3" id="addThisFormContainer">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title" id="cardTitle">Update stock</h3>
                    </div>
                    <div class="card-body">
                        <div class="ermsg"></div>
                        <form id="createThisForm">
                            @csrf
                            <input type="hidden" id="deleted_purchase_histories" name="deleted_purchase_histories" value="">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="purchase_date">Purchase Date</label>
                                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" placeholder="Enter purchase date" value="{{ $purchase->purchase_date }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="supplier_id">Select Supplier</label>
                                        <select class="form-control" id="supplier_id" name="supplier_id" disabled>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" data-balance="{{ $supplier->balance }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="supplier_balance">Supplier Balance</label>
                                        <input type="text" class="form-control" id="supplier_balance" name="supplier_balance" placeholder="Enter supplier previous due" readonly>
                                        <input type="hidden" id="previous_purchase_due" value="{{ $purchase->due_amount }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="invoice">Invoice</label>
                                        <input type="text" class="form-control" id="invoice" name="invoice" placeholder="Enter invoice" value="{{ $purchase->invoice }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="vat_reg">VAT Reg#</label>
                                        <input type="text" class="form-control" id="vat_reg" name="vat_reg" placeholder="Enter VAT Reg#" value="{{ $purchase->vat_reg }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="purchase_type">Payment Type</label>
                                        <select class="form-control" id="purchase_type" name="purchase_type">
                                            <option value="">Select...</option>
                                            <option value="cash" {{ $purchase->purchase_type == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank" {{ $purchase->purchase_type == 'bank' ? 'selected' : '' }}>Bank</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="ref">Ref</label>
                                        <input type="text" class="form-control" id="ref" name="ref" placeholder="Enter reference" value="{{ $purchase->ref }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="1" placeholder="Enter remarks">{{ $purchase->remarks }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="product_id">Choose Product</label>
                                        <select class="form-control" id="product_id" name="product_id">
                                            <option value="">Select...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-name="{{ $product->name }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="unit_price">Unit Price</label>
                                        <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" placeholder="Enter unit price">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="product_size">Size</label>
                                        <select class="form-control" id="product_size" name="product_size">
                                            <option value="">Select...</option>
                                            <option value="XS">XS</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="product_color">Color</label>
                                        <select class="form-control" id="product_color" name="product_color">
                                            <option value="">Select...</option>
                                            <option value="Black">Black</option>
                                            <option value="White">White</option>
                                            <option value="Red">Red</option>
                                            <option value="Blue">Blue</option>
                                            <option value="Green">Green</option>                                     
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <label for="addProductBtn">Action</label>
                                    <div class="col-auto d-flex align-items-end">
                                        <button type="button" id="addProductBtn" class="btn btn-secondary">Add</button>
                                     </div>
                                </div>

                                <div class="col-sm-12 mt-3">
                                    <h2>Product List:</h2>
                                    <table class="table table-bordered" id="productTable">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Size</th>
                                                <th>Color</th>
                                                <th>Unit Price</th>
                                                <th>VAT %</th>
                                                <th>VAT Amount</th>
                                                <th>Total Price</th>
                                                <th>Total Price with VAT</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productTable">
                                            @foreach($purchase->purchaseHistory as $history)
                                            <tr data-id="{{ $history->id }}" data-product-id="{{ $history->product->id }}">
                                                <td>{{ $history->product->name }}</td>
                                                <td><input type="number" class="form-control quantity" value="{{ $history->quantity }}" /></td>
                                                <td>{{ $history->product_size }}</td>
                                                <td>{{ $history->product_color }}</td>
                                                <td><input type="number" step="0.01" class="form-control unit_price" value="{{ $history->purchase_price }}" /></td>
                                                <td><input type="number" step="0.01" class="form-control vat_percent" value="{{ $history->vat_percent }}" /></td>
                                                <td>{{ $history->vat_amount }}</td>
                                                <td>{{ $history->total_price }}</td>
                                                <td>{{ $history->total_price_with_vat }}</td>
                                                <td><button type="button" class="btn btn-sm btn-danger remove-product">Remove</button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-12 mt-4 mb-5">
                                    <div class="row justify-content-end">
                                        <div class="col-sm-3 d-flex align-items-center">
                                            <span class="">Item Total Amount:</span>
                                            <input type="text" class="form-control" id="item_total_amount" readonly style="width: 100px; margin-left: auto;">
                                        </div>
                                    </div>
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-sm-3 d-flex align-items-center">
                                            <span class="">Discount Amount:</span>
                                            <input type="number" step="0.01" class="form-control" id="discount" name="discount" style="width: 100px; margin-left: auto;" value="{{ $purchase->discount }}">
                                            <input type="hidden" id="hidden_discount" value="{{ $purchase->discount }}">
                                        </div>
                                    </div>
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-sm-3 d-flex align-items-center">
                                            <span class="">Total VAT Amount:</span>
                                            <input type="text" class="form-control" id="total_vat_amount" readonly style="width: 100px; margin-left: auto;">
                                        </div>
                                    </div>
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-sm-3 d-flex align-items-center">
                                            <span class="">Net Amount:</span>
                                            <input type="text" class="form-control" id="net_amount" readonly style="width: 100px; margin-left: auto;">
                                        </div>
                                    </div>
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-sm-3 d-flex align-items-center">
                                            <span class="">Paid Amount:</span>
                                            <input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" style="width: 100px; margin-left: auto;" value="{{ $purchase->paid_amount }}">
                                            <input type="hidden" id="hidden_paid_amount" value="{{ $purchase->paid_amount }}">
                                        </div>
                                    </div>
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-sm-3 d-flex align-items-center">
                                            <span class="">Due Amount:</span>
                                            <input type="text" class="form-control" id="due_amount" readonly style="width: 100px; margin-left: auto;">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" id="addBtn" class="btn btn-secondary" value="Create"><i class="fas fa-sync-alt"></i> Update</button>    
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

<script>
    $(document).ready(function() {

        var deletedPurchaseHistories = [];

        function updateSummary() {
            var itemTotalAmount = 0;
            var totalVatAmount = 0;

            $('#productTable tbody tr').each(function() {
                var purchaseHistoryId = $(this).data('id');
                var productId = $(this).data('product-id');
                var quantity = parseFloat($(this).find('input.quantity').val()) || 0;
                var unitPrice = parseFloat($(this).find('input.unit_price').val()) || 0;
                var vatPercent = parseFloat($(this).find('input.vat_percent').val()) || 0;

                var totalPrice = (quantity * unitPrice).toFixed(2);
                var vatAmount = (totalPrice * vatPercent / 100).toFixed(2);
                var totalPriceWithVat = (parseFloat(totalPrice) + parseFloat(vatAmount)).toFixed(2);

                $(this).find('td:eq(7)').text(totalPrice);
                $(this).find('td:eq(8)').text(totalPriceWithVat);
                $(this).find('td:eq(6)').text(vatAmount);

                itemTotalAmount += parseFloat(totalPrice) || 0;
                totalVatAmount += parseFloat(vatAmount) || 0;
            });

            $('#item_total_amount').val(itemTotalAmount.toFixed(2) || '0.00');
            var discount = parseFloat($('#discount').val()) || 0;
            var netAmount = itemTotalAmount + totalVatAmount - discount;
            $('#total_vat_amount').val(totalVatAmount.toFixed(2) || '0.00');
            $('#net_amount').val(netAmount.toFixed(2) || '0.00');
            var paidAmount = parseFloat($('#paid_amount').val()) || 0;
            var dueAmount = isNaN(paidAmount) ? netAmount : netAmount - paidAmount;
            $('#due_amount').val(dueAmount.toFixed(2) || '0.00');
        }

        updateSummary();

        $('#addProductBtn').click(function() {
            var selectedSize = $('#product_size').val() || 'M';
            var selectedColor = $('#product_color').val() || 'Black';

            var selectedProduct = $('#product_id option:selected');
            var productId = selectedProduct.val();
            var productName = selectedProduct.data('name');
            var quantity = $('#quantity').val();
            var unitPrice = $('#unit_price').val();
            var vatPercent = parseFloat($('#vat_percent').val()) || 5;

            if (isNaN(quantity) || quantity <= 0) {
                alert('Quantity must be a positive number.');
                return;
            }

            var totalPrice = (quantity * unitPrice).toFixed(2);
            var vatAmount = (totalPrice * vatPercent / 100).toFixed(2);
            var totalPriceWithVat = (parseFloat(totalPrice) + parseFloat(vatAmount)).toFixed(2);

            var productExists = false;
            $('#productTable tbody tr').each(function() {
                var existingProductId = $(this).data('product-id');
                var existingSize = $(this).find('td:eq(2)').text();
                var existingColor = $(this).find('td:eq(3)').text();

                if (productId == existingProductId && selectedSize == existingSize && selectedColor == existingColor) {
                    productExists = true;
                    return false;
                }
            });

            if (productExists) {
                alert('The same product with the selected size and color already exists.');
                return;
            }

            if (productId && quantity && unitPrice) {
                var productRow = `<tr data-id="" data-product-id="${productId}">
                                    <td>${productName}</td>
                                    <td><input type="number" class="form-control quantity" value="${quantity}" /></td>
                                    <td>${selectedSize}</td>
                                    <td>${selectedColor}</td>
                                    <td><input type="number" step="0.01" class="form-control unit_price" value="${unitPrice}" /></td>
                                    <td><input type="number" step="0.01" class="form-control vat_percent" value="${vatPercent}" /></td>
                                    <td>${vatAmount}</td>
                                    <td>${totalPrice}</td>
                                    <td>${totalPriceWithVat}</td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-product">Remove</button></td>
                                </tr>`;
                $('#productTable tbody').append(productRow);
                $('#quantity').val('');
                $('#unit_price').val('');
                $('#product_size').val('');
                $('#product_color').val('');

                updateSummary();
            }
        });

        $(document).on('click', '.remove-product', function() {
            var purchaseHistoryId = $(this).closest('tr').data('id');
            if (purchaseHistoryId) {
                deletedPurchaseHistories.push(purchaseHistoryId);
                $('#deleted_purchase_histories').val(deletedPurchaseHistories.join(','));
            }
            $(this).closest('tr').remove();
            updateSummary();
        });

        $('#paid_amount').on('input', function() {
            updateSummary();
        });

        $(document).on('input', '#productTable input.quantity, #productTable input.unit_price, #productTable input.vat_percent', function() {
            updateSummary();
        });

        $('#discount').on('input', function() {
            updateSummary();
        });

        $('#addBtn').on('click', function(e) {
            e.preventDefault();
            var formData = {};
            var selectedProducts = [];

            formData.purchase_id = {{ $purchase->id }};
            formData.invoice = $('#invoice').val();
            formData.purchase_date = $('#purchase_date').val();
            formData.supplier_id = $('#supplier_id').val();
            formData.previous_purchase_due = $('#previous_purchase_due').val();
            formData.vat_reg = $('#vat_reg').val();
            formData.purchase_type = $('#purchase_type').val();
            formData.ref = $('#ref').val();
            formData.remarks = $('#remarks').val();

            formData.total_amount = $('#item_total_amount').val();
            formData.discount = $('#discount').val();
            formData.hidden_discount = $('#hidden_discount').val();
            formData.total_vat_amount = $('#total_vat_amount').val();
            formData.net_amount = $('#net_amount').val();
            formData.paid_amount = $('#paid_amount').val();
            formData.hidden_paid_amount = $('#hidden_paid_amount').val();
            formData.due_amount = $('#due_amount').val();

            $('#productTable tbody tr').each(function() {
                var purchaseHistoryId = $(this).data('id');
                var productId = $(this).data('product-id');
                var quantity = $(this).find('input.quantity').val();
                var unitPrice = $(this).find('input.unit_price').val();
                var productSize = $(this).find('td:eq(2)').text(); 
                var productColor = $(this).find('td:eq(3)').text();
                var vatPercent = $(this).find('input.vat_percent').val();
                var vatAmount = $(this).find('td:eq(6)').text();
                var totalPrice = $(this).find('td:eq(7)').text();
                var totalPriceWithVat = $(this).find('td:eq(8)').text();

                selectedProducts.push({
                    purchase_history_id: purchaseHistoryId,
                    product_id: productId,
                    quantity: quantity,
                    product_size: productSize,
                    product_color: productColor,
                    unit_price: unitPrice,
                    vat_percent: vatPercent,
                    vat_amount: vatAmount,
                    total_price: totalPrice,
                    total_price_with_vat: totalPriceWithVat
                });
            });

            var finalData = { ...formData, products: selectedProducts };
            // console.log(finalData);

            $.ajax({
                url: '/admin/update-stock',
                method: 'POST',
                data: finalData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    swal({
                        text: "Updated successfully",
                        icon: "success",
                        button: {
                            text: "OK",
                            className: "swal-button--confirm"
                        }
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#product_id').select2({
            placeholder: "Select product...",
            allowClear: true,
            width: '100%'
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supplierSelect = document.getElementById('supplier_id');
        const supplierPrevDue = document.getElementById('supplier_balance');

        function updateSupplierBalance() {
            const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
            const balance = selectedOption.getAttribute('data-balance');
            supplierPrevDue.value = balance ? balance : '0.00';
        }
        updateSupplierBalance();
        supplierSelect.addEventListener('change', updateSupplierBalance);
    });
</script>

@endsection