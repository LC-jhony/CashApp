# AGENTS.md - CashApp Development Guide

## Project Overview
Laravel 12 app with Filament 5 admin, Vite + Tailwind CSS v4. Manages loans with multiple amortization methods (French, German, American).

## Technology Stack
- **Backend:** Laravel 12, PHP 8.2+ | **Admin:** Filament 5 | **Frontend:** Vite 7, Tailwind CSS 4
- **Testing:** Pest PHP | **Database:** SQLite (testing), MySQL/PostgreSQL (prod)

---

## Build / Lint / Test Commands

### Setup
```bash
composer setup                    # Full setup (deps + assets)
composer install && npm install && npm run build
```

### Development
```bash
composer dev                      # Run all dev services (server, queue, logs, vite)
php artisan serve                 # PHP server only
npm run dev                       # Vite dev server
php artisan queue:listen --tries=1
```

### Testing
```bash
composer test                     # Run all tests
php artisan test                  # Via artisan
php artisan test tests/Unit/ExampleTest.php  # Specific file
php artisan test --filter="test_name"        # Single test (RECOMMENDED)
php artisan test --coverage       # With coverage
php artisan test --testsuite=Unit/Feature   # Suite
./vendor/bin/pest --filter="test_name"
```

### Code Quality
```bash
./vendor/bin/pint --format agent  # Auto-fix style (REQUIRED before commit)
./vendor/bin/pint --test          # Check only (no fix)
php artisan config:clear          # Clear caches after changes
```

### Database
```bash
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed
```

---

## Code Style Guidelines

### PHP Conventions
- **PSR-12** compliance via Laravel Pint
- Use `declare(strict_types=1);` in new files
- Always use curly braces for control structures, even single-line
- Use explicit return types and parameter type hints

### Constructor Property Promotion
```php
public function __construct(public GitHub $github) { }
```

### Naming Conventions
| Element | Convention | Example |
|---------|------------|---------|
| Models, Controllers | PascalCase | `Loan`, `LoanController` |
| Traits | PascalCase + Trait | `TraitFrances`, `TraitAleman` |
| Methods, Variables | camelCase | `calculateAmortization()`, `$amount` |
| Database columns | snake_case | `user_id`, `created_at` |
| Filament Resources | PascalCase + Resource | `LoanResource` |
| Enums | TitleCase keys | `FavoritePerson`, `Monthly` |

### Import Order (use IDE auto-organize)
1. PHP built-in / Core Laravel
2. Composer packages
3. Application imports (`App\Models\*`, `App\Filament\*`)
4. Trait imports (`use App\Trait\*`)

```php
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\Loan;
use App\Trait\TraitFrances;
```

### Filament Resource Structure
```
app/Filament/Resources/[Name]/
├── [Name]Resource.php      # Main resource
├── Schemas/
│   ├── [Name]Form.php
│   └── [Name]Infolist.php
├── Tables/
│   └── [Name]Table.php
└── Pages/
    ├── List[Name].php, Create[Name].php, Edit[Name].php, View[Name].php
```

### Error Handling
- Use try-catch for complex operations
- Log errors: `\Log::error('message: ' . $e->getMessage())`
- Return early with defaults on validation failure

### Model Conventions
- Define `$fillable` for mass assignment
- Use `$casts` method (not property) for date/JSON columns
- Define relationships with return type hints

---

## Testing (Pest PHP)

```php
test('description', function () {
    expect($record->field)->toBe(expected);
});
```

- Use `test()` and `expect()` - NOT `it()`
- Group in `tests/Feature/` or `tests/Unit/`
- Create with: `php artisan make:test --pest {name}`
- Run: `php artisan test --compact --filter=testName`
- Activate `pest-testing` skill for test tasks

---

## Common Patterns

### Amortization Traits
- `TraitFrances` - French (constant payment)
- `TraitAleman` - German (declining payment)
- `TraitAmericano` - American (interest-only)

Each provides: `PayM()` (payment amount), `PlanMensual()` (schedule)

### Filament Forms
```php
Select::make('type')->options(LoanType::class)->required()->live(),
TextInput::make('field')->visible(fn (Get $get): bool => $get('type') === 'value'),
```

### Important Notes
- Run `npm run build` if frontend changes don't appear
- Use `search-docs` for Laravel/Filament/Pest/Tailwind documentation
- Activate `tailwindcss-development` skill for CSS tasks
- Use `tinker` for debugging models, `database-query` for read queries
