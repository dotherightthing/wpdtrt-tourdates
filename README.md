# DTRT WP Tour Dates

Display the relative position of content within an assigned date-range.

Used for:

*  Permalinks? - no, manually set
*  Page title
*  Prev/Next navigation
*  Post Page Heading 1 (post day number)
*  Post Archive Heading 2 (category day range)
*  Post Archive Heading 3 (post day number)
*  Post Archive anchor nav (category day duration)

Example: 10/7/2017-13/7/2017 = Day 1, Day 2, Day 3, Day 4

## Setup

```
// 1. Install PHP dependencies
composer install

// 2. Install Node dependencies into the parent plugin's folder
npm --prefix ./vendor/dotherightthing/wpdtrt-plugin/ install ./vendor/dotherightthing/wpdtrt-plugin/

// 3. Run the parent plugin's Gulp tasks against the contents of the child plugin's folder
// 4. Watch for changes to the child plugin's folder
gulp --gulpfile ./vendor/dotherightthing/wpdtrt-plugin/gulpfile.js --cwd ./
```

## Usage

Please read the [WordPress readme.txt](readme.txt) for usage instructions.
