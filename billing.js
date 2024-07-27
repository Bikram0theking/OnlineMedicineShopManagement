$(document).ready(function() {
    function addMedicineGroup() {
        const medicineGroup = `
            <div class="medicine-group">
                <div class="form-group">
                    <label for="medicineName">Medicine Name</label>
                    <input type="text" class="form-control medicineName" placeholder="Enter medicine name">
                    <div class="medicineList list-group" style="display:none;"></div>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control quantity" placeholder="Enter quantity">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control price" readonly>
                </div>
                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="text" class="form-control total" readonly>
                </div>
                <button type="button" class="btn btn-danger remove-medicine">Remove</button>
            </div>`;
        $('#medicines').append(medicineGroup);
    }

    $('#medicines').on('input', '.medicineName', function() {
        let query = $(this).val();
        let currentElement = $(this);
        if (query.length >= 2) {
            $.ajax({
                url: 'fetch_medicines.php',
                method: 'POST',
                data: { query: query },
                success: function(data) {
                    currentElement.siblings('.medicineList').fadeIn();
                    currentElement.siblings('.medicineList').html(data);
                }
            });
        } else {
            currentElement.siblings('.medicineList').fadeOut();
        }
    });

    $('#medicines').on('click', '.medicine-item', function() {
        let medicineName = $(this).text();
        let currentGroup = $(this).closest('.medicine-group');
        currentGroup.find('.medicineName').val(medicineName);
        currentGroup.find('.medicineList').fadeOut();

        $.ajax({
            url: 'fetch_medicine_details.php',
            method: 'POST',
            data: { medicineName: medicineName },
            success: function(data) {
                let medicineDetails = JSON.parse(data);
                currentGroup.find('.price').val(medicineDetails.price);
                calculateTotal(currentGroup);
            }
        });
    });

    $('#medicines').on('input', '.quantity', function() {
        let currentGroup = $(this).closest('.medicine-group');
        calculateTotal(currentGroup);
    });

    function calculateTotal(group) {
        let quantity = group.find('.quantity').val();
        let price = group.find('.price').val();
        let total = quantity * price;
        group.find('.total').val(total.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.total').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#grandTotal').text(grandTotal.toFixed(2));
    }

    $('#billingForm').on('click', '.remove-medicine', function() {
        $(this).closest('.medicine-group').remove();
        calculateGrandTotal();
    });

    $('.add-medicine').click(function() {
        addMedicineGroup();
    });

    $('#billingForm').on('submit', function(e) {
        e.preventDefault();
        let customerName = $('#customerName').val();
        let customerPhone = $('#customerPhone').val();
        let customerAddress = $('#customerAddress').val();
        let billingDate = $('#billingDate').val();
        let medicines = [];

        $('.medicine-group').each(function() {
            let medicineName = $(this).find('.medicineName').val();
            let quantity = $(this).find('.quantity').val();
            let price = $(this).find('.price').val();
            let total = $(this).find('.total').val();
            medicines.push({ medicineName, quantity, price, total });
        });

        $.ajax({
            url: 'generate_bill.php',
            method: 'POST',
            data: {
                customerName,
                customerPhone,
                customerAddress,
                billingDate,
                medicines
            },
            success: function(response) {
                let billData = JSON.parse(response);
                displayBill(billData);
                updateStock(billData.medicines);
            }
        });
    });

    function displayBill(billData) {
        let billContent = `
            <h3>ProBiShuk Medicine Shop</h3>
            <p>Date: ${billData.billingDate}</p>
            <p>Customer Name: ${billData.customerName}</p>
            <p>Customer Phone: ${billData.customerPhone}</p>
            <p>Customer Address: ${billData.customerAddress}</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>`;
        billData.medicines.forEach(medicine => {
            billContent += `
                <tr>
                    <td>${medicine.medicineName}</td>
                    <td>${medicine.quantity}</td>
                    <td>${medicine.price}</td>
                    <td>${medicine.total}</td>
                </tr>`;
        });
        billContent += `
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">Grand Total</td>
                        <td>${billData.grandTotal}</td>
                    </tr>
                </tfoot>
            </table>`;

        $('#billContent').html(billContent);
        $('#billDisplay').show();
        
        // Print the bill
        window.print();
    }

    function updateStock(medicines) {
        $.ajax({
            url: 'update_stock.php',
            method: 'POST',
            data: { medicines },
            success: function(response) {
                console.log('Stock updated successfully');
            }
        });
    }
});
