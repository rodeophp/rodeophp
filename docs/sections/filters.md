Filters narrow the index listing to a subset of records. They are declared on the table via `->filters([...])` and appear as dropdown controls above the table on the index page.

### Declaring filters

```php
public static function table(Table $table): Table
{
    return $table->columns([
        // ...
    ])->filters([
        SelectFilter::make('breed')->options([
            'quarter'   => 'Quarter Horse',
            'mustang'   => 'Mustang',
            'appaloosa' => 'Appaloosa',
        ]),
        BooleanFilter::make('is_saddled'),
    ]);
}
```

### SelectFilter

An exact-match dropdown. The `options` array defines both the dropdown choices and the allowlist of accepted values. Submitted values not present as keys are silently ignored.

```php
SelectFilter::make('status')->options([
    'active'   => 'Active',
    'inactive' => 'Inactive',
    'pending'  => 'Pending',
]),
```

On application, the filter adds a `WHERE column = value` clause to the query.

### BooleanFilter

A Yes/No dropdown over a boolean column. Accepts `'1'` (truthy) and `'0'` (falsy) as values; anything else is ignored.

```php
BooleanFilter::make('is_saddled'),
```

### Query string shape

Active filters are read from `filter[name]=value` query string parameters. For example:

```
/admin/resources/horses?filter[breed]=mustang&filter[is_saddled]=1
```

Multiple filters can be active at the same time. Each one is applied as an additional `WHERE` clause, so they combine with AND logic.

### Combining with search

Filters and full-text search work together. When both are active, the query applies search conditions first (as an `orWhere` group across searchable columns) and then adds each active filter on top.
