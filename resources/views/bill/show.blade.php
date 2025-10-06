<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Splitter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .name-input {
            margin-bottom: 30px;
        }

        .name-input label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .name-input input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .name-input input:focus {
            outline: none;
            border-color: #667eea;
        }

        .items-list {
            margin-bottom: 30px;
        }

        .items-header {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 600;
            color: #555;
        }

        .item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .item:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .item.selected {
            border-color: #667eea;
            background: #f0f3ff;
        }

        .item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            cursor: pointer;
        }

        .item-name {
            flex: 1;
            font-size: 16px;
            color: #333;
        }

        .item-price {
            font-weight: 600;
            color: #667eea;
            font-size: 18px;
        }

        .summary {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 16px;
        }

        .summary-row.total {
            border-top: 2px solid #ddd;
            padding-top: 12px;
            margin-top: 12px;
            font-weight: 700;
            font-size: 20px;
            color: #667eea;
        }

        .tip-options {
            margin-bottom: 20px;
        }

        .tip-header {
            margin-bottom: 12px;
            font-weight: 600;
            color: #555;
        }

        .tip-buttons {
            display: flex;
            gap: 10px;
        }

        .tip-button {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .tip-button:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .tip-button.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .error-message {
            color: #e74c3c;
            padding: 12px;
            background: #fee;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧾 Bill Splitter</h1>

        <div class="error-message" id="errorMessage"></div>

        <div class="name-input">
            <label for="userName">Your Name</label>
            <input type="text" id="userName" placeholder="Enter your name">
        </div>

        <div class="items-list">
            <div class="items-header">Select your items:</div>
            @foreach($items as $index => $item)
            <div class="item" onclick="toggleItem(event, {{ $index }})">
                <input type="checkbox" id="item{{ $index }}">
                <span class="item-name">{{ $item['name'] }}</span>
                <span class="item-price">${{ number_format($item['price'], 2) }}</span>
            </div>
            @endforeach
        </div>

        <div class="tip-options">
            <div class="tip-header">Add a tip:</div>
            <div class="tip-buttons">
                <button class="tip-button" onclick="selectTip(10)">10%</button>
                <button class="tip-button" onclick="selectTip(15)">15%</button>
                <button class="tip-button" onclick="selectTip(20)">20%</button>
                <button class="tip-button" onclick="selectTip(0)">No Tip</button>
            </div>
        </div>

        <div class="summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span id="subtotal">$0.00</span>
            </div>
            <div class="summary-row">
                <span>Tip (<span id="tipPercent">0</span>%):</span>
                <span id="tipAmount">$0.00</span>
            </div>
            <div class="summary-row total">
                <span>Your Total:</span>
                <span id="total">$0.00</span>
            </div>
        </div>
    </div>

    <script>
        const items = @json($items);
        let selectedTipPercent = 0;

        function toggleItem(event, index) {
            // Only toggle if not clicking directly on the checkbox
            if (event.target.type !== 'checkbox') {
                const checkbox = document.getElementById('item' + index);
                checkbox.checked = !checkbox.checked;
                updateItemStyle(index);
                updateTotal();
            }
        }

        function updateItemStyle(index) {
            const item = document.querySelectorAll('.item')[index];
            const checkbox = document.getElementById('item' + index);
            if (checkbox.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        }

        function selectTip(percent) {
            selectedTipPercent = percent;
            
            document.querySelectorAll('.tip-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            event.target.classList.add('active');
            
            updateTotal();
        }

        function updateTotal() {
            let subtotal = 0;
            items.forEach((item, index) => {
                const checkbox = document.getElementById('item' + index);
                if (checkbox.checked) {
                    subtotal += parseFloat(item.price);
                }
            });

            const tipAmount = subtotal * (selectedTipPercent / 100);
            const total = subtotal + tipAmount;

            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('tipPercent').textContent = selectedTipPercent;
            document.getElementById('tipAmount').textContent = '$' + tipAmount.toFixed(2);
            document.getElementById('total').textContent = '$' + total.toFixed(2);
        }

        // Listen for checkbox changes
        document.addEventListener('DOMContentLoaded', function() {
            items.forEach((item, index) => {
                const checkbox = document.getElementById('item' + index);
                checkbox.addEventListener('change', function() {
                    updateItemStyle(index);
                    updateTotal();
                });
            });
        });
    </script>
</body>
</html>
