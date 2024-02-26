# Theme

- **class** `Theme` (`utils\helpers\gui\Theme`)
- **package** `helpers`
- **source** `utils/helpers/gui/Theme.php`

**Description**

Class Theme

---

#### Properties

- `->`[`vars`](#prop-vars) : `mixed`

---

#### Static Methods

- `Theme ::`[`applyTo()`](#method-applyto)
- `Theme ::`[`removeFrom()`](#method-removefrom)
- `Theme ::`[`makeLocalPath()`](#method-makelocalpath)
- `Theme ::`[`reload()`](#method-reload)

---

#### Methods

- `->`[`setVar()`](#method-setvar)
- `->`[`removeVar()`](#method-removevar)
- `->`[`getVar()`](#method-getvar)
- `->`[`save()`](#method-save)
- `->`[`load()`](#method-load)
- `->`[`generateToStyle()`](#method-generatetostyle)

---
# Static Methods

<a name="method-applyto"></a>

### applyTo()
```php
Theme::applyTo(mixed $target, mixed $path): void
```

---

<a name="method-removefrom"></a>

### removeFrom()
```php
Theme::removeFrom(mixed $target, mixed $path): void
```

---

<a name="method-makelocalpath"></a>

### makeLocalPath()
```php
Theme::makeLocalPath(mixed $path): string
```

---

<a name="method-reload"></a>

### reload()
```php
Theme::reload(mixed $target, mixed $path): void
```

---
# Methods

<a name="method-setvar"></a>

### setVar()
```php
setVar(mixed $var, mixed $value): void
```

---

<a name="method-removevar"></a>

### removeVar()
```php
removeVar(mixed $var): void
```

---

<a name="method-getvar"></a>

### getVar()
```php
getVar(mixed $var): mixed
```

---

<a name="method-save"></a>

### save()
```php
save(mixed $path): void
```

---

<a name="method-load"></a>

### load()
```php
load(mixed $path): void
```

---

<a name="method-generatetostyle"></a>

### generateToStyle()
```php
generateToStyle(mixed $path): void
```