app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── OuvrageController.php
│   │   │   ├── CategorieController.php
│   │   │   ├── GammeController.php
│   │   │   ├── MediaController.php
│   │   │   └── SettingsController.php
│   │   └── Public/
│   │       └── ...
│   ├── Requests/
│   │   └── Admin/
│   │       ├── OuvrageRequest.php
│   │       ├── CategorieRequest.php
│   │       └── GammeRequest.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── Ouvrage.php
│   ├── Categorie.php
│   ├── Gamme.php
│   ├── Media.php
│   └── Settings.php
└── View/
    └── Components/
        └── Admin/
            ├── Layout/
            └── Forms/





            resources/views/admin/
├── layouts/
│   └── admin.blade.php
├── partials/
│   ├── sidebar.blade.php
│   └── topbar.blade.php
├── dashboard/
│   └── index.blade.php
├── ouvrages/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── categories/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── gammes/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── medias/
│   └── index.blade.php
└── settings/
    └── index.blade.php