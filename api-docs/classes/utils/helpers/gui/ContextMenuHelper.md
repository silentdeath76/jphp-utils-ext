# ContextMenuHelper

- **class** `ContextMenuHelper` (`utils\helpers\gui\ContextMenuHelper`)
- **source** `utils/helpers/gui/ContextMenuHelper.php`

---

#### Properties

- `->`[`target`](#prop-target) : `UXContextMenu`
- `->`[`config`](#prop-config) : `Configuration`

---

#### Static Methods

- `ContextMenuHelper ::`[`of()`](#method-of)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _ContextMenuHelper constructor._
- `->`[`addItem()`](#method-additem)
- `->`[`addCategory()`](#method-addcategory)
- `->`[`getTarget()`](#method-gettarget)
- `->`[`addSeparator()`](#method-addseparator)
- `->`[`makeIcon()`](#method-makeicon)
- `->`[`setGraphic()`](#method-setgraphic)
- `->`[`configurate()`](#method-configurate)

---
# Static Methods

<a name="method-of"></a>

### of()
```php
ContextMenuHelper::of(mixed $target, php\util\Configuration $config): ContextMenuHelper
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(mixed $target, mixed $config): void
```
ContextMenuHelper constructor.

---

<a name="method-additem"></a>

### addItem()
```php
addItem(mixed $text, mixed $callback, php\gui\UXNode $graphic): UXMenuItem
```

---

<a name="method-addcategory"></a>

### addCategory()
```php
addCategory(mixed $text, callable|null $callback, php\gui\UXNode $graphic): ContextMenuHelper
```

---

<a name="method-gettarget"></a>

### getTarget()
```php
getTarget(): void
```

---

<a name="method-addseparator"></a>

### addSeparator()
```php
addSeparator(): void
```

---

<a name="method-makeicon"></a>

### makeIcon()
```php
makeIcon(mixed $file): UXImageView
```

---

<a name="method-setgraphic"></a>

### setGraphic()
```php
setGraphic(mixed $node, mixed $graphic): void
```

---

<a name="method-configurate"></a>

### configurate()
```php
configurate(mixed $node): void
```