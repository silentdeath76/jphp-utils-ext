# Mutex

- **class** `Mutex` (`utils\helpers\Mutex`)
- **source** `utils/helpers/Mutex.php`

---

#### Properties

- `->`[`lock`](#prop-lock) : `SharedValue`

---

#### Methods

- `->`[`__construct()`](#method-__construct)
- `->`[`synchronize()`](#method-synchronize)
- `->`[`isBusy()`](#method-isbusy)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```

---

<a name="method-synchronize"></a>

### synchronize()
```php
synchronize(callable $callback): void
```

---

<a name="method-isbusy"></a>

### isBusy()
```php
isBusy(): void
```