<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            // Major Currencies (Top priority)
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 1, 'is_default' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.92],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'exchange_rate' => 0.79],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'exchange_rate' => 149.50, 'decimal_places' => 0],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'exchange_rate' => 7.24],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'exchange_rate' => 83.12],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'exchange_rate' => 1.53],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'exchange_rate' => 1.36],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'exchange_rate' => 0.88, 'symbol_position' => 'after'],
            ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$', 'exchange_rate' => 7.82],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$', 'exchange_rate' => 1.34],
            ['code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$', 'exchange_rate' => 1.64],
            
            // South Asian
            ['code' => 'BDT', 'name' => 'Bangladeshi Taka', 'symbol' => '৳', 'exchange_rate' => 110.25],
            ['code' => 'PKR', 'name' => 'Pakistani Rupee', 'symbol' => '₨', 'exchange_rate' => 278.50],
            ['code' => 'LKR', 'name' => 'Sri Lankan Rupee', 'symbol' => 'Rs', 'exchange_rate' => 323.00],
            ['code' => 'NPR', 'name' => 'Nepalese Rupee', 'symbol' => 'रू', 'exchange_rate' => 133.20],
            
            // Southeast Asian
            ['code' => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM', 'exchange_rate' => 4.72],
            ['code' => 'THB', 'name' => 'Thai Baht', 'symbol' => '฿', 'exchange_rate' => 35.80],
            ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'exchange_rate' => 15750, 'decimal_places' => 0],
            ['code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱', 'exchange_rate' => 56.20],
            ['code' => 'VND', 'name' => 'Vietnamese Dong', 'symbol' => '₫', 'exchange_rate' => 24500, 'decimal_places' => 0, 'symbol_position' => 'after'],
            
            // Middle East
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'exchange_rate' => 3.67],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼', 'exchange_rate' => 3.75],
            ['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => 'ر.ق', 'exchange_rate' => 3.64],
            ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك', 'exchange_rate' => 0.31],
            ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => 'BD', 'exchange_rate' => 0.38],
            ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => 'ر.ع.', 'exchange_rate' => 0.38],
            ['code' => 'ILS', 'name' => 'Israeli Shekel', 'symbol' => '₪', 'exchange_rate' => 3.67],
            ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺', 'exchange_rate' => 32.15],
            ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => 'E£', 'exchange_rate' => 30.90],
            ['code' => 'JOD', 'name' => 'Jordanian Dinar', 'symbol' => 'JD', 'exchange_rate' => 0.71],
            
            // African
            ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R', 'exchange_rate' => 18.65],
            ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦', 'exchange_rate' => 1550],
            ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'exchange_rate' => 153.50],
            ['code' => 'GHS', 'name' => 'Ghanaian Cedi', 'symbol' => 'GH₵', 'exchange_rate' => 12.35],
            ['code' => 'MAD', 'name' => 'Moroccan Dirham', 'symbol' => 'د.م.', 'exchange_rate' => 10.05],
            ['code' => 'TZS', 'name' => 'Tanzanian Shilling', 'symbol' => 'TSh', 'exchange_rate' => 2520],
            ['code' => 'UGX', 'name' => 'Ugandan Shilling', 'symbol' => 'USh', 'exchange_rate' => 3780, 'decimal_places' => 0],
            ['code' => 'ETB', 'name' => 'Ethiopian Birr', 'symbol' => 'Br', 'exchange_rate' => 56.80],
            
            // European
            ['code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr', 'exchange_rate' => 10.42, 'symbol_position' => 'after'],
            ['code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr', 'exchange_rate' => 10.68, 'symbol_position' => 'after'],
            ['code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr', 'exchange_rate' => 6.87, 'symbol_position' => 'after'],
            ['code' => 'PLN', 'name' => 'Polish Zloty', 'symbol' => 'zł', 'exchange_rate' => 3.97, 'symbol_position' => 'after'],
            ['code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč', 'exchange_rate' => 23.25, 'symbol_position' => 'after'],
            ['code' => 'HUF', 'name' => 'Hungarian Forint', 'symbol' => 'Ft', 'exchange_rate' => 358, 'decimal_places' => 0, 'symbol_position' => 'after'],
            ['code' => 'RON', 'name' => 'Romanian Leu', 'symbol' => 'lei', 'exchange_rate' => 4.58, 'symbol_position' => 'after'],
            ['code' => 'BGN', 'name' => 'Bulgarian Lev', 'symbol' => 'лв', 'exchange_rate' => 1.80, 'symbol_position' => 'after'],
            ['code' => 'HRK', 'name' => 'Croatian Kuna', 'symbol' => 'kn', 'exchange_rate' => 6.95, 'symbol_position' => 'after'],
            ['code' => 'RSD', 'name' => 'Serbian Dinar', 'symbol' => 'дин', 'exchange_rate' => 108.50, 'symbol_position' => 'after'],
            ['code' => 'UAH', 'name' => 'Ukrainian Hryvnia', 'symbol' => '₴', 'exchange_rate' => 37.50],
            ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽', 'exchange_rate' => 92.50],
            
            // Americas
            ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => 'MX$', 'exchange_rate' => 17.15],
            ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$', 'exchange_rate' => 4.97],
            ['code' => 'ARS', 'name' => 'Argentine Peso', 'symbol' => 'AR$', 'exchange_rate' => 875],
            ['code' => 'CLP', 'name' => 'Chilean Peso', 'symbol' => 'CL$', 'exchange_rate' => 935, 'decimal_places' => 0],
            ['code' => 'COP', 'name' => 'Colombian Peso', 'symbol' => 'CO$', 'exchange_rate' => 3950, 'decimal_places' => 0],
            ['code' => 'PEN', 'name' => 'Peruvian Sol', 'symbol' => 'S/', 'exchange_rate' => 3.72],
            ['code' => 'UYU', 'name' => 'Uruguayan Peso', 'symbol' => '$U', 'exchange_rate' => 38.50],
            ['code' => 'VES', 'name' => 'Venezuelan Bolivar', 'symbol' => 'Bs', 'exchange_rate' => 36.50],
            ['code' => 'JMD', 'name' => 'Jamaican Dollar', 'symbol' => 'J$', 'exchange_rate' => 155.50],
            ['code' => 'TTD', 'name' => 'Trinidad Dollar', 'symbol' => 'TT$', 'exchange_rate' => 6.78],
            
            // East Asian
            ['code' => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩', 'exchange_rate' => 1325, 'decimal_places' => 0],
            ['code' => 'TWD', 'name' => 'Taiwan Dollar', 'symbol' => 'NT$', 'exchange_rate' => 31.85],
            
            // Crypto (for reference)
            ['code' => 'USDT', 'name' => 'Tether USD', 'symbol' => '₮', 'exchange_rate' => 1],
            
            // Pacific
            ['code' => 'FJD', 'name' => 'Fiji Dollar', 'symbol' => 'FJ$', 'exchange_rate' => 2.25],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                array_merge([
                    'symbol_position' => 'before',
                    'decimal_places' => 2,
                    'decimal_separator' => '.',
                    'thousand_separator' => ',',
                    'is_active' => true,
                    'is_default' => false,
                ], $currency)
            );
        }
    }
}
