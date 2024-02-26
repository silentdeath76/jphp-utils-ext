# Eventable

- **class** `Eventable` (`utils\helpers\traits\Eventable`)
- **source** `utils/helpers/traits/Eventable.php`

---

#### Properties

- `->`[`events`](#prop-events) : `mixed`
- `->`[`enable`](#prop-enable) : `mixed`

---

#### Methods

- `->`[`on()`](#method-on)
- `->`[`off()`](#method-off)
- `->`[`trigger()`](#method-trigger)
- `->`[`isEnabled()`](#method-isenabled)
- `->`[`disable()`](#method-disable)
- `->`[`enable()`](#method-enable)

---
# Methods

<a name="method-on"></a>

### on()
```php
on(mixed $event, mixed $callback, mixed $group): void
```

---

<a name="method-off"></a>

### off()
```php
off(mixed $event, mixed $group): void
```

---

<a name="method-trigger"></a>

### trigger()
```php
trigger(mixed $event, mixed $args, mixed $group): void
```

---

<a name="method-isenabled"></a>

### isEnabled()
```php
isEnabled(): void
```

---

<a name="method-disable"></a>

### disable()
```php
disable(): void
```

---

<a name="method-enable"></a>

### enable()
```php
enable(): void
```