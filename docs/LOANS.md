# Sistema de Préstamos (Loans)

## Descripción General

El sistema de préstamos permite registrar y gestionar préstamos con diferentes métodos de amortización (Francés, Alemán, Americano) y frecuencias de pago (Mensual, Bimestral, Trimestral, Semestral, Anual).

---

## Arquitectura de Archivos

```
app/
├── Models/
│   ├── Loan.php                    # Modelo principal del préstamo
│   ├── Plan.php                    # Modelo de plan de pagos
│   ├── Frecuencie.php              # Frecuencias de pago
│   └── Rate.php                    # Tasas de interés
│
├── Trait/
│   ├── TraitFrances.php            # Amortización método Francés
│   ├── TraitAleman.php             # Amortización método Alemán
│   └── TraitAmericano.php          # Amortización método Americano
│
├── Observers/
│   └── LoanObserver.php            # Genera planes automáticamente
│
└── Filament/Resources/Loans/
    ├── LoanResource.php            # Configuración del recurso
    ├── Schemas/
    │   ├── LoanForm.php            # Formulario (crear/editar)
    │   └── LoanInfolist.php        # Vista de solo lectura
    ├── Tables/
    │   └── LoansTable.php          # Listado de préstamos
    └── Pages/
        ├── ListLoans.php           # Página de listado
        ├── CreateLoan.php          # Página de creación
        ├── EditLoan.php            # Página de edición
        └── ViewLoan.php            # Página de visualización
```

---

## Modelos

### Loan (`app/Models/Loan.php`)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `amount` | float | Monto del préstamo |
| `frecuency_id` | int | FK a frecuencias |
| `rate_id` | int | FK a tasas |
| `years` | int | Plazo en años |
| `amort_method` | string | FRANCES, ALEMAN, AMERICANO |
| `user_id` | int | Usuario que crea |
| `customer_id` | int | Cliente (opcional) |

**Relaciones:**
- `plan()` → HasMany → Plan
- `frecuency()` → BelongsTo → Frecuencie
- `rate()` → BelongsTo → Rate
- `customer()` → BelongsTo → Customer

### Plan (`app/Models/Plan.php`)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `loan_id` | int | FK al préstamo |
| `date` | date | Fecha de pago |
| `number` | int | Número de cuota |
| `payment` | float | Monto de la cuota |
| `interest` | float | Interés del período |
| `amort` | float | Amortización del capital |
| `balance` | float | Saldo pendiente |

---

## Traits de Amortización

Los traits contienen la lógica matemática para calcular las tablas de amortización.

### TraitFrances (`app/Trait/TraitFrances.php`)

**Características:**
- Cuota fija durante todo el préstamo
- Intereses decrecientes
- Amortización de capital creciente

**Métodos:**
- `PMT($interest, $num_of_payments, $pv)` - Fórmula de pago
- `PlanMensual($rate, $amount, $years)` - Tabla mensual
- `PlanBimestral($rate, $amount, $years)` - Tabla bimestral
- `PlanTrimestral($rate, $amount, $years)` - Tabla trimestral
- `PlanSemestral($rate, $amount, $years)` - Tabla semestral
- `PlanAnual($rate, $amount, $years)` - Tabla anual

### TraitAleman (`app/Trait/TraitAleman.php`)

**Características:**
- Amortización de capital fija
- Cuota decreciente
- Intereses decrecientes

### TraitAmericano (`app/Trait/TraitAmericano.php`)

**Características:**
- Solo se pagan intereses durante el plazo
- El capital se paga completo en la última cuota

---

## Observer: LoanObserver (`app/Observers/LoanObserver.php`)

El observer automatiza la generación de planes de pago.

### Eventos:

| Evento | Acción |
|--------|--------|
| `created` | Genera todos los planes de pago |
| `updated` | Si cambian parámetros clave, regenera planes |
| `deleted` | Elimina todos los planes asociados |

### Flujo:

```
Loan creado
    ↓
LoanObserver::created()
    ↓
generatePlans()
    ↓
calculateAmortizationTable()
    ↓
[TraitFrances|TraitAleman|TraitAmericano]
    ↓
Plan::insert() → Guarda en BD
```

---

## Recurso Filament: LoanResource

### Formulario (`app/Filament/Resources/Loans/Schemas/LoanForm.php`)

**Campos:**
1. **Parámetros del préstamo:**
   - `amount` - Monto
   - `frecuency_id` - Frecuencia
   - `rate_id` - Tasa de interés
   - `years` - Plazo
   - `amort_method` - Método de amortización

2. **Cuadro de marcha (Repeater):**
   - `date` - Fecha
   - `payment` - Cuota
   - `amort` - Amortización
   - `interest` - Intereses
   - `balance` - Pendiente

3. **Totales:**
   - `total_pagado`
   - `total_amortizacion`
   - `total_intereses`
   - `total_pendiente`

**Reactividad:**
Los campos principales tienen `->live()` y `afterStateUpdated()` para calcular la amortización en tiempo real mientras el usuario cambia los valores.

### Vista (`app/Filament/Resources/Loans/Schemas/LoanInfolist.php`)

Muestra los datos del préstamo en modo solo lectura:
- Parámetros del préstamo
- Cuadro de marcha (tabla con todos los planes)
- Totales calculados desde la relación `plan`

### Edición (`app/Filament/Resources/Loans/Pages/EditLoan.php`)

Usa `mutateFormDataBeforeFill()` para:
1. Leer datos del préstamo
2. Calcular la tabla de amortización
3. Llenar el formulario con planes y totales

---

## Flujo de Datos Completo

### Creación de Préstamo

```
Usuario → CreateLoan (Filament)
    ↓
LoanForm::configure()
    ↓
Usuario completa: amount, frecuency_id, rate_id, years, amort_method
    ↓
afterStateUpdated() → calculateAmortization()
    ↓
[TraitFrances|TraitAleman|TraitAmericano]
    ↓
$set('plans', [...]) → Vista previa en tiempo real
    ↓
Usuario hace clic en "Crear"
    ↓
Loan::create() → Guarda en BD
    ↓
LoanObserver::created()
    ↓
generatePlans() → Plan::insert() → Guarda planes en BD
```

### Edición de Préstamo

```
Usuario → EditLoan (Filament)
    ↓
mutateFormDataBeforeFill()
    ↓
calculateAmortizationTable()
    ↓
[TraitFrances|TraitAleman|TraitAmericano]
    ↓
$data['plans'] = [...] → Llena formulario
    ↓
Usuario ve cuadro de marcha
    ↓
Usuario modifica parámetros → afterStateUpdated() recalcula
    ↓
Usuario hace clic en "Guardar"
    ↓
Loan::update()
    ↓
LoanObserver::updated()
    ↓
plan()->delete() + generatePlans() → Regenera planes
```

### Visualización de Préstamo

```
Usuario → ViewLoan (Filament)
    ↓
LoanInfolist::configure()
    ↓
RepeatableEntry::make('plan') → Carga desde BD
    ↓
$record->plan → Relación HasMany
    ↓
Muestra tabla con todos los planes guardados
```

---

## Comparación de Métodos de Amortización

| Método | Cuota | Amortización | Intereses | Caso de Uso |
|--------|-------|--------------|-----------|-------------|
| **Francés** | Fija | Creciente | Decreciente | Créditos hipotecarios, personales |
| **Alemán** | Decreciente | Fija | Decreciente | Préstamos comerciales |
| **Americano** | Solo intereses | Al final | Fijos | Bonos, deuda corporativa |

---

## Ejemplo de Cálculo

**Préstamo:** $10,000 USD, 12% anual, 1 año, Mensual, Francés

| Cuota | Fecha | Pago | Amortización | Interés | Balance |
|-------|-------|------|--------------|---------|---------|
| 1 | 2026-03-18 | 888.49 | 788.49 | 100.00 | 9,211.51 |
| 2 | 2026-04-18 | 888.49 | 796.37 | 92.12 | 8,415.14 |
| ... | ... | ... | ... | ... | ... |
| 12 | 2027-02-18 | 888.49 | 879.69 | 8.80 | 0.00 |

**Totales:**
- Total Pagado: $10,661.88
- Total Amortización: $10,000.00
- Total Intereses: $661.88

---

## Notas Técnicas

1. **Eager Loading:** Al mostrar préstamos, usar `with('plan')` para evitar N+1
2. **Transacciones:** El observer opera dentro de la transacción del modelo
3. **Precisión:** Los valores se redondean a 2 decimales
4. **Fechas:** Se calculan desde `Carbon::now()` sumando períodos

---

## Mantenimiento

### Agregar nueva frecuencia:
1. Agregar registro en tabla `frecuencies`
2. Agregar método en los 3 traits (ej: `PlanCuatrimestral()`)
3. Actualizar `match()` en `LoanObserver` y `EditLoan`

### Agregar nuevo método de amortización:
1. Crear nuevo trait (ej: `TraitPersonalizado.php`)
2. Agregar `use` en `LoanObserver`, `EditLoan`, `LoanForm`
3. Agregar caso en `match()` de `calculateAmortizationTable()`
4. Agregar opción en Select de `amort_method`
