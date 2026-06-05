The `table()` method on a resource configures the index listing: which columns to show, how they can be sorted or searched, and which filters are available. Columns are declared inside `Table::columns([...])`.

### TextColumn

Renders the raw attribute value as text.

```php
TextColumn::make('name')->sortable()->searchable(),
TextColumn::make('created_at')->date('M j, Y')->sortable(),
```

| Modifier | Effect |
|---|---|
| `sortable()` | Allows the column to be sorted by clicking its header. |
| `searchable()` | Includes this column in the panel's full-text search. |
| `label(string)` | Overrides the auto-generated column heading. |
| `date(string $format)` | Formats a `DateTimeInterface` attribute with the given format string. The default format when `date()` is called without an argument is `Y-m-d H:i`. |

### BadgeColumn

Renders a pill badge. Use `colors()` to map option values to color tokens.

```php
BadgeColumn::make('breed')->colors([
    'quarter'   => 'accent',
    'mustang'   => 'ink',
    'appaloosa' => 'muted',
]),
```

Available color tokens: `accent`, `ink`, `muted`. Values not present in the map are rendered without a color token.

### BooleanColumn

Renders a check mark for truthy values and a dash for falsy ones.

```php
BooleanColumn::make('is_saddled'),
```

The resolved value is cast to a real `bool` before being passed to the frontend.

### CustomColumn

Renders a custom element supplied by a plugin. The column sets `value` and `column` DOM properties on the element (read-only; no input event expected). See the Plugins section for details.

```php
CustomColumn::make('mood')->tag('mood-cell'),
```

### Relation columns and eager loading

Dotted names read through a loaded relation:

```php
TextColumn::make('rider.name')->label('Rider'),
```

For this to work, the relation must already be loaded. Declare `$with` on the resource to eager-load it on every index query:

```php
public static array $with = ['rider'];
```

Relation columns are not sortable or searchable in the current release.

### Search behavior

The panel applies a `LIKE %search%` query across all columns marked `searchable()`, combined with `orWhere` clauses. The search term arrives via the `search` query string parameter.

### Sort behavior

Clicking a sortable column header toggles between ascending and descending order. The active sort and direction are reflected in the `sort` and `direction` query string parameters. When no valid sort is requested, records default to descending primary key order.
