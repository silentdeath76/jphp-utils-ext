# FormResizer

- **class** `FormResizer` (`utils\helpers\gui\FormResizer`)
- **package** `helpers`
- **source** `utils/helpers/gui/FormResizer.php`

**Description**

Class FormResizer

---

#### Properties

- `->`[`debug`](#prop-debug) : `mixed`
- `->`[`disabled`](#prop-disabled) : `mixed`
- `->`[`imageGreen`](#prop-imagegreen) : `mixed`
- `->`[`imageTransparent`](#prop-imagetransparent) : `mixed`
- `->`[`clickPos`](#prop-clickpos) : `mixed`
- `->`[`screenClickPos`](#prop-screenclickpos) : `mixed`
- `->`[`items`](#prop-items) : `mixed`
- `->`[`form`](#prop-form) : `UXForm`
- `->`[`formSize`](#prop-formsize) : `array`

---

#### Static Methods

- `FormResizer ::`[`init()`](#method-init)
- `FormResizer ::`[`createPoint()`](#method-createpoint)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _FormResizer constructor._
- `->`[`setTitleOutput()`](#method-settitleoutput)
- `->`[`createControls()`](#method-createcontrols)
- `->`[`enable()`](#method-enable)
- `->`[`disable()`](#method-disable)
- `->`[`minSize()`](#method-minsize)

---
# Static Methods

<a name="method-init"></a>

### init()
```php
FormResizer::init(php\gui\UXForm $form, array $config): \utils\helpers\FormResizer
```

---

<a name="method-createpoint"></a>

### createPoint()
```php
FormResizer::createPoint(callable $callback, array $size, array $position, bool $color): \php\gui\UXImageArea
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(php\gui\UXForm $form, array $config): void
```
FormResizer constructor.

---

<a name="method-settitleoutput"></a>

### setTitleOutput()
```php
setTitleOutput(php\gui\UXNode $node): void
```

---

<a name="method-createcontrols"></a>

### createControls()
```php
createControls(): void
```

---

<a name="method-enable"></a>

### enable()
```php
enable(): void
```

---

<a name="method-disable"></a>

### disable()
```php
disable(): void
```

---

<a name="method-minsize"></a>

### minSize()
```php
minSize(int $width, int $height): void
```