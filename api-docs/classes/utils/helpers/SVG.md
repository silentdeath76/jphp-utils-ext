# SVG

- **class** `SVG` (`utils\helpers\SVG`)
- **source** `utils/helpers/SVG.php`

---

#### Properties

- `->`[`scale`](#prop-scale) : `mixed`

---

#### Static Methods

- `SVG ::`[`of()`](#method-of)

---

#### Methods

- `->`[`__construct()`](#method-__construct)
- `->`[`apply()`](#method-apply)
- `->`[`resize()`](#method-resize)
- `->`[`scale()`](#method-scale)

---
# Static Methods

<a name="method-of"></a>

### of()
```php
SVG::of(mixed $path, int $scale): SVG
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(mixed $data, mixed $scale): void
```

---

<a name="method-apply"></a>

### apply()
```php
apply(php\gui\UXCanvas $target, string $color): UXCanvas
```

---

<a name="method-resize"></a>

### resize()
```php
resize(mixed $width, mixed $height, bool $resizeContainer): SVG
```

---

<a name="method-scale"></a>

### scale()
```php
scale(mixed $svg, mixed $scale): mixed|string
```