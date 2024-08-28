<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="https://kit.fontawesome.com/f3f9dcc437.js" crossorigin="anonymous"></script>
    <title>Personal Finance Tracker</title>
</head>
<body>
    <main id="main">
        <div id='main-display'>
        <div id="logo-container" style="display: flex; align-items: center; margin-bottom: 20px;">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" style="margin-right: 10px; width:4rem" />
                <span style="font-size: 14px; font-weight: bold;">ExpenseEye</span>
            </div>
            <p>Hello,
                <span id='welcome'>{{ Auth::user()->first_name }}</span>!
            </p>
            <div id='display-expense'>
                <p>Total :</p>
                <div id='dis-flex'>
                    <h2 id='curr'>&#36; </h2>
                    <h1 id='total-exp'>
                        {{ number_format($transactions->sum('amount'), 2) }}
                    </h1>
                </div>
            </div>
            <div id='display-bottom'>
                <p id='brk'>Breakdown</p>
                <i class="fas fa-angle-down"></i>
            </div>
            <div id='scroll'>
                <div id='records'>
                    @foreach($transactions as $transaction)
                        <div id="bottom-records" data-id="{{ $transaction->id }}">
                        <div id="icon-box">
                                {!! getCategoryIcon($transaction->category->name) !!}
                            </div>
                            <div id="item-text1">
                                <h1 id="item-h1">{{ $transaction->description }}</h1>
                                <p>{{ $transaction->category->name }}</p>
                            </div>
                            <div id="item-text2">
                                <h2 id="item-price">&#36;{{ number_format($transaction->amount, 2) }}</h2>
                                <p>{{ $transaction->transaction_date }}</p>
                            </div>
                            <div id="item-edit" data-id="{{ $transaction->id }}">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div id="item-delete" data-id="{{ $transaction->id }}">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div id='main-profile'>
            <div id='top-profile'>
                <div id="user-profile">
                    <div id="user-image">
                        <img id='userImage' src='{{ asset('img/user.png') }}' />
                    </div>
                    <h1 id='h1'>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
                    <a href="{{ route('profile.edit') }}">
                        <button id='profile'>Profile</button>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button id='sign-out'>Sign Out</button>
                    </form>

                </div>
                <div id="tabs">
                    <button class="tab" id="tab-30-days" data-range="30-days">Last 30 Days</button>
                    <button class="tab" id="tab-current-month" data-range="current-month">{{ now()->format('F Y') }}</button>
                    <button class="tab" id="tab-90-days" data-range="90-days">Last 90 Days</button>
                    <button class="tab" id="tab-current-year" data-range="current-year">{{ now()->format('Y') }}</button>
                </div>
                
                <div id="custom-date-range">
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date">
                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date">
                    <button id="show-custom-range">Show</button>
                </div>
   <p>Daily Spending Change (vs. Last Month): 
    <span id="spending-trend">
        (
        {{ $trendPercentage >= 0 ? '+' : '' }}{{ number_format($trendPercentage, 1) }}%)
    </span>
</p>
                <div id="expense-chart">
                    <div id="expense-summary">
                        <br>
                        <p>Total Spent: <span id="total-spent">{{ number_format($transactions->sum('amount'), 2) }}</span></p>
                        <p>Highest Expense Category: <span id="highest-category">{{ $highestCategoryName ?? 'N/A' }}</span></p>
                        <p>Number of Transactions: <span id="num-transactions">{{ $transactions->count() }}</span></p>
                        <p>Average Daily Spending: <span id="avg-daily">{{ number_format($transactions->sum('amount') / $totalDays, 2) }}</span></p>
                    </div>
                    <div id="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>                    
                </div>
            </div>
            <div id='bottom-profile'>
                <div id='icons'>
                    <div id='icon-box-form-1'>
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div id='icon-box-form-2'>
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
                <div id='bottom-form'>
                <form id='form' action="{{ route('transactions.store') }}" method="POST">
    @csrf
    <input type="hidden" id="transaction_id" name="transaction_id">
    <input type="text" id="amount" name="amount" placeholder='Amount' required><br>
    <input type="text" id='place' name='description' placeholder="Place of Spend" required><br>
    <label for="category">Category:</label>
    <div class="category-container">
        <select id="category" name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="button" id="add-category" title="Add a new category">+</button>
    </div>
    <br>
    <label for="date">Date:</label>
    <input type="date" id="date" name="transaction_date" required><br>
    <button type="submit" id='submit'> Add to Expense </button>
</form>

                </div>
            </div>
        </div>
    </main>
    <div class="app-name" style="text-align: center;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 3%;">
        ExpenseEye
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function showStats(range, startDate = null, endDate = null) {
                const url = new URL(window.location.href);
                url.searchParams.set('range', range);
                if (range === 'custom') {
                    url.searchParams.set('start_date', startDate);
                    url.searchParams.set('end_date', endDate);
                }
                window.location.href = url.href;
            }

            function setActiveTab(range) {
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.dataset.range === range) {
                        tab.classList.add('active');
                    }
                });
            }

            document.querySelectorAll('.tab').forEach(button => {
                button.addEventListener('click', function() {
                    const range = this.dataset.range;
                    setActiveTab(range);
                    showStats(range);
                });
            });

            document.getElementById('show-custom-range').addEventListener('click', () => {
                const startDate = document.getElementById('start-date').value;
                const endDate = document.getElementById('end-date').value;
                setActiveTab('custom');
                showStats('custom', startDate, endDate);
            });

            const urlParams = new URLSearchParams(window.location.search);
            const range = urlParams.get('range') || '30-days';
            setActiveTab(range);

            document.querySelectorAll('#item-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const transactionId = this.dataset.id;
                    fetch(`/transactions/${transactionId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            location.reload();
                        } else {
                            alert('Failed to delete transaction');
                        }
                    }).catch(error => {
                        console.error('Error deleting transaction:', error);
                        alert('Failed to delete transaction');
                    });
                });
            });

            document.querySelectorAll('#item-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const transactionId = this.dataset.id;
                    fetch(`/transactions/${transactionId}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(transaction => {
                        document.getElementById('transaction_id').value = transaction.id;
                        document.getElementById('amount').value = transaction.amount;
                        document.getElementById('place').value = transaction.description;
                        document.getElementById('category').value = transaction.category_id;
                        document.getElementById('date').value = transaction.transaction_date;
                        document.getElementById('submit').innerText = 'Save Changes';
                        document.getElementById('form').action = `/transactions/${transaction.id}`;
                        document.getElementById('form').method = 'POST';
                        const methodInput = document.createElement('input');
                        methodInput.setAttribute('type', 'hidden');
                        methodInput.setAttribute('name', '_method');
                        methodInput.setAttribute('value', 'PUT');
                        document.getElementById('form').appendChild(methodInput);
                    })
                    .catch(error => {
                        console.error('Error fetching transaction:', error);
                        alert('Failed to fetch transaction');
                    });
                });
            });

            document.getElementById('form').addEventListener('submit', function(event) {
                const methodInput = document.querySelector('input[name="_method"]');
                if (!methodInput) {
                    return;
                }

                event.preventDefault();
                const transactionId = document.getElementById('transaction_id').value;
                fetch(`/transactions/${transactionId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: document.getElementById('amount').value,
                        description: document.getElementById('place').value,
                        category_id: document.getElementById('category').value,
                        transaction_date: document.getElementById('date').value
                    })
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to save transaction');
                    }
                }).catch(error => {
                    console.error('Error saving transaction:', error);
                    alert('Failed to save transaction');
                });
            });

            document.getElementById('add-category').addEventListener('click', function() {
                const categoryName = prompt("Enter the new category name:");

                if (categoryName) {
                    const formattedCategoryName = categoryName.charAt(0).toUpperCase() + categoryName.slice(1).toLowerCase();

                    fetch('/categories', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name: formattedCategoryName })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(category => {
                        const categorySelect = document.getElementById('category');
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.text = category.name;
                        categorySelect.appendChild(option);
                        categorySelect.value = category.id;
                    })
                    .catch(error => {
                        console.error('Error adding category:', error);
                        alert('Failed to add category');
                    });
                }
            });
                
        });
    </script>
</body>
</html>
