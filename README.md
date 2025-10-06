# Bill Splitter Web

Laravel application that works in conjunction with the bill-splitter mobile app.

## Features

- **REST API** for generating QR codes from JSON bill data
- **Web interface** for users to select their items and calculate their share
- **Tip calculation** with options for 10%, 15%, 20%, or no tip
- **Responsive design** that works on mobile devices

## Installation

1. Clone the repository
2. Install dependencies:
```bash
composer install
```

3. Copy `.env.example` to `.env` and configure your database
4. Generate application key:
```bash
php artisan key:generate
```

5. Run migrations:
```bash
php artisan migrate
```

6. Start the development server:
```bash
php artisan serve
```

## API Usage

### Generate QR Code from Bill

**Endpoint:** `POST /api/bills/generate-qr`

**Request Body:**
```json
{
  "items": [
    {
      "name": "Margherita Pizza",
      "price": 12.99
    },
    {
      "name": "Caesar Salad",
      "price": 8.50
    },
    {
      "name": "Garlic Bread",
      "price": 4.99
    },
    {
      "name": "Lemonade",
      "price": 3.50
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "uuid": "c773b072-f362-466a-ac2e-a9d59b7bece0",
  "url": "http://localhost:8000/bill/c773b072-f362-466a-ac2e-a9d59b7bece0",
  "qr_code": "iVBORw0KGgoAAAANSUhEUgAA..." 
}
```

The `qr_code` field contains a base64-encoded PNG image that can be displayed to users. When scanned, it directs them to the bill splitting page.

## Bill Splitting Interface

When users scan the QR code or visit the URL, they can:

1. Enter their name
2. Select which items they consumed
3. Choose a tip percentage (10%, 15%, 20%, or no tip)
4. See their calculated total in real-time

## Testing

Run the test suite:
```bash
php artisan test
```

## Example cURL Request

```bash
curl -X POST http://localhost:8000/api/bills/generate-qr \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "items": [
      {"name": "Margherita Pizza", "price": 12.99},
      {"name": "Caesar Salad", "price": 8.50},
      {"name": "Garlic Bread", "price": 4.99},
      {"name": "Lemonade", "price": 3.50}
    ]
  }'
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
