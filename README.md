# Smoke Testgen: Cover your Symfony app with Smoke Tests in seconds

**Experimental**: SmokeTestgen is an experimental project under active development. It is not yet stable, may contain bugs or undergo breaking changes. It's build it in the open with the community feedback.

[![Downloads total](https://img.shields.io/packagist/dt/rector/jack.svg?style=flat-square)](https://packagist.org/packages/rector/jack/stats)

<br>

In real world, "jack" is a tool that helps to raise your heavy car one inch at a time. So you can fix any issues down there and drive safely on journeys to come.

<br>

<img src="/docs/jack.jpg" alt="SmokeTestgen" width="300" align="center">

<br>

In Composer world, SmokeTestgen helps you to raise dependencies one version at a time, safely and steadily.

Say goodbye to unnoticed, years-old dependencies!

<br>

## Why SmokeTestgen?

Manually upgrading dependencies can be daunting, especially when tackling multiple outdated packages at once. Large upgrades often lead to errors, compatibility issues, and costly delays.

SmokeTestgen automates and simplifies this process by:

- Monitoring outdated dependencies via CI.
- Gradually opening up package versions for safe updates.
- Prioritizing low-risk updates (e.g., dev dependencies).

<br>

## Install

Rector SmokeTestgen is downgraded and scoped. It requires **PHP 7.2+** and can be installed on any legacy project.

```bash
composer require rector/jack --dev
```

Then, pick from three powerful commands:

<br>

## 1. Too many Outdated Dependencies? Let CI tell us

Postponing upgrades often results in large, risky jumps (e.g., updating once a 3 years). SmokeTestgen integrates with your CI pipeline to catch outdated dependencies early.

Run the `breakpoint` command to check for **outdated major packages**:

```bash
vendor/bin/jack breakpoint
```

↓

<img src="/docs/breakpoint.png" alt="SmokeTestgen" width="600">


<br>

If there are more than 5 major outdated packages, the **CI will fail**.

<br>

Use `--limit` to raise or lower your bar:

```bash
vendor/bin/jack breakpoint --limit 3
```

This ensures upgrades stay on your radar without overwhelming you. No more "oops, our 30 dependencies are 5 years old" moments!

<br>

It's safer to start upgrading dev packages first. You can spot them like this:

```bash
vendor/bin/jack breakpoint --dev
```

<br>

## 2. Open up Next Versions

We know we're behind the latest versions of our dependencies, but where to start? Which versions should be force to update first? We can get lot of conflicts if we try to bump wrong end of knot.

Instead, let Composer handle it. How? We open-up package versions to the next version:

```bash
vendor/bin/jack open-versions
```

This command opens up 5 versions to their next nearest step, e.g.:

```diff
 {
     "require": {
         "php": "^7.4",
-            "symfony/console": "5.1.*"
+            "symfony/console": "5.1.*|5.2.*"
         },
         "require-dev": {
-            "phpunit/phpunit": "^9.0"
+            "phpunit/phpunit": "^9.0|^10.0"
         }
     }
 }
```

Then we run Composer to do the work:

```bash
composer update
```

If no blockers exist, Composer will update packages to their next version.

<br>

To change the number of packages, use `--limit` option:

```bash
vendor/bin/jack open-versions --limit 3
```

<br>

To upgrade only specific group of packages, use `--package-prefix` option:

```bash
vendor/bin/jack open-versions --package-prefix symfony
```

<br>

To preview changes without modifying `composer.json`, add `--dry-run`.

<br>

Do you want to play it safe? Try low-risk dev packages first:

```bash
vendor/bin/jack open-versions --dev
```

<br>

## 3. Raise to Installed Versions

Sometimes, we get to an opposite situation. Our dependencies are quite new, but our `composer.json` is a outdated:

<img src="/docs/composer-outdated-install.png" alt="Outdated composer.json" width="450" align="center">

Here we can see that:

* `illuminate/container` 12.0 is allowed, but we already use 12.14
* `symfony/finder` 6.4 is allowed, but we already use 7.2

If someone runs `composer update`, they might get unnecessary older dependencies than we can handle. Also, we're self-deprecating out project by signalling old dependencies we don't even use.

Instead, we should raise our `composer.json` to the installed versions:

```diff
 {
     "require": {
         "php": "^7.4",
-        "illuminate/container": "^12.0",
+        "illuminate/container": "^12.14",
         // ...
-        "symfony/finder": "^6.4|^7.2",
+        "symfony/finder": "^7.2",
         // ...
     }
 }
```

That's exactly what following command does:

```bash
vendor/bin/jack raise-to-installed
```

To see changes first without applying, add `--dry-run`.

<br>

Happy coding!
