## 3.8.11.52 safe rail + hero pagination finish
- Rolls back the v151 contained-hero geometry and keeps the proven v149/v150 Events-reference layout.
- Keeps the hero full width while its copy aligns to the measured header rail.
- Adds one accessible clickable hero pager without changing hero width or slide media.
- Restores `wpbb-v150-trust-shell` as a measured-rail container and keeps the four trust items on the same grid as the rest of the homepage.
- Reinforces one horizontal rail for section shells, headings, cards, newsletter and footer while removing nested horizontal gutters only.
- Adds consistent breathing room between the trust separator and Shop by category.

## 3.8.11.50 targeted section stability
- Keeps the working v149 Events-parity rail and the stable v144 WooCommerce catalogue/product/account repairs.
- Fixes the trust/spec row collapse by removing the second viewport offset from the inner BBuilder row.
- Converts only the homepage gallery Swiper into a deterministic 4/2/1 card grid, preventing thin image-strip collapse while leaving hero and partner sliders untouched.
- Restores process step badges to compact 32px markers instead of full-card rounded overlays.
- Uses four distinct gallery images and keeps every repaired section on the measured header rail.

## 3.8.11.49 Events-reference layout recovery
- Retires the accumulated v119-v148 frontend geometry/runtime layers and returns to the shared v118 base used by the stable Events child.
- Uses the actual rendered header container to measure the canonical left/right content edge, matching the Events 3.8.11.40 architecture.
- Restores the hero as a full-width v118 slider with high-quality child-owned media and copy aligned to the same measured content rail.
- Keeps full-width section backgrounds while every inner heading/card row shares the same measured grid.
- Uses the real Tech Shop DB row classes for category, service, setup, stats, case-study and process grids.
- Preserves the v144 deterministic product fallback and server-side Woo/My Account routing while replacing its conflicting frontend geometry.
- Keeps gallery Swiper behaviour and gives Latest Thinking a stable 3/2/1 card grid.
- Retains the Events-style WooCommerce catalogue/product/cart/checkout/account layout rules in the single final owner.

## 3.8.11.16 reset-safe final release fixes
- Demo reset/import now re-runs the canonical managed BBuilder page rebuild and all v116 repairs automatically.
- The old migration that removed legitimate responsive BBuilder column widths is disabled; desktop multi-column layouts survive a clean demo reset.
- Desktop mega menus use the measured header bottom plus a hover bridge, matching the close Jobs positioning across all children.
- Home hero sliders use three distinct child-owned images, visible pagination, 8.5-second autoplay and pause-on-hover with sharp natural-scale rendering.
- Managed demo/editorial/catalogue/gallery/Woo media is restored from bundled files; Woo Clothes keeps the complete bundled product-image pool.
- Quote drawers use resilient trigger detection and sit flush to the right viewport edge on quote-enabled themes.
- Cookie-consent acceptance persists across reloads using a stable browser marker.
- Legal pages remain left-aligned on the normal grid; Latest Thinking media/card edges are normalized.
- Partner/brand serialization is normalized idempotently to prevent repeated wrappers and the `wpbb/column` validation warning.
- Theme Settings retain child-owned controls for disabling dark mode and keeping English-only Polylang content.

## 3.8.11.14 child-only settings, editor, legal, editorial and hero finish

- Latest Thinking card rows now use the same 1320px grid as their headings; the 1440px row override that shifted the first card left has been removed.
- Hero images use a direct child-owned native source, stronger left-edge gradient masking and no CSS blur/viewport stretching.
- Appearance > Theme Settings adds switches to disable dark mode and disable translations/keep English only. Enabling English-only moves non-English Polylang Pages and Posts to Trash and hides the language switcher.
- Privacy/Terms/Cookies content spans the normal site grid and is left-aligned instead of being forced into a centered narrow column.
- The known raw partner-heading serialization defect is repaired in imported pages and on future page saves, resolving the wpbb/column validation error.
- Child editor CSS is moved from enqueue_block_editor_assets to enqueue_block_assets for the WordPress editor iframe.

## 3.8.11.13 child-only hero and editorial grid finish

- Latest Thinking / related editorial cards use the full 1440px site grid; the legacy outer BBuilder row and list start padding can no longer create a first-card left inset.
- Homepage heroes use a native-resolution child asset without viewport-width stretching.
- A stronger white-to-transparent hero gradient crosses the photograph's left edge so the image seam is hidden.
- Existing and translated managed hero blocks are refreshed from the same child-owned asset after upgrade.

## 3.8.11.12 child-only visual/media fixes

- Latest Thinking card grid now inherits the section grid with no first-card left inset.
- Sharper child-owned hero source and late frontend override.
- Managed catalogue and editorial media are re-synchronised from bundled child assets.
- Media/text CTA buttons align to the copy edge.

## 3.8.11.11 media and WooCommerce finalisation

- Repairs missing demo media from bundled local assets, including cloned-site WooCommerce product images and Automotive vehicle finder thumbnails.
- Forces WooCommerce filters, ranges, compare controls and product actions to the child theme accent instead of the plugin blue fallback.
- Uses a two-column desktop Basket, Checkout and My Account shell with mobile stacking only below 821px.
- Uses the highest-resolution bundled hero source during managed demo rebuilds; Business uses the 1600x1000 office source.
- Requires parent WP BBTheme 3.8.10.23 for reliable My Account header URLs on cloned sites.

## 3.8.10.82 suite consistency

Requires WP BBuilder 5.6.9+ for palette inheritance and the shared hCaptcha verifier. This release keeps the sector's individual brand colour while using the same 1440px canvas, card/form rhythm, dark-mode baseline and footer/newsletter hierarchy as the rest of the 15-theme suite. The one-time cleanup is restricted to records explicitly marked as theme-managed demo content.

## 3.8.10.65

- Fixes the Theme Settings frontend-protection panel so its CSS is loaded in the admin head instead of appearing as visible text.
- Makes sector media repair load the WordPress image API safely before generating attachment metadata.
- Refines shared card, directory, gallery and responsive alignment.
- Adds block-theme Single Product, Cart and Checkout templates while preserving the existing classic-template fallback.

## 3.8.10.47

- More compact and consistent section spacing, cards and responsive layouts.
- Smaller in-frame gallery thumbnail pagination and improved light/dark contrast.
- Reliable child-owned WooCommerce product shells where the theme includes commerce.

# WP BBTheme Child Woo Tech Shop 3.8.10.65
Technology WooCommerce child theme. Reusable ecommerce filtering/minicart functionality remains in **WP Theme Woo Support**; this theme owns presentation and Woo page shells.

## v3.7 classic Woo customer journey

The child now routes the front end through version-safe PHP shells for:

- Shop and product taxonomy catalogue (retaining the shared AJAX filter/results layer).
- Single product through the installed WooCommerce legacy product engine.
- Basket/Cart through `WC_Shortcode_Cart`.
- Checkout and order-received through `WC_Shortcode_Checkout`.
- My Account and endpoints through `WC_Shortcode_My_Account`.

This deliberately uses the legacy templates shipped by the **installed WooCommerce version** instead of bundling stale copies. The child adds the polished tables, forms, checkout/order, account navigation, responsive behaviour and product-gallery theme support.

The latest supplied localhost DB reports WooCommerce 11.0.1 with HPOS enabled.

Run `yarn prod`. After upgrading, run **Appearance → Starter Setup → Import / Refresh Starter Website**.

### 3.8.10.46
- Dashboard-safe, resumable sector media repair; no synchronous bulk image regeneration on `admin_init`.
- Password protection controls live under **Theme Settings → General**.
- Thumbnail navigation is overlaid inside the main gallery image.
- Active-sector Blog and directory media are repaired after child-theme switching.

## SCSS structure (3.8.10.9)

Frontend styles are split into `tokens`, `tools`, `base`, `header`, `footer`, `components`, `swiper`, `motion`, `forms`, `blog`, `quality`, `sector`, `responsive` and `features`. Fluid typography uses the suite `fluid-font()` mixin and explicit viewport guards rather than `clamp()`. The generated production CSS intentionally contains no `!important` declarations.

### Build compatibility

The child build is dependency-free and works with Yarn 1.22.x as well as newer Yarn versions. No Corepack step is required. Use:

```sh
yarn prod
```

The command runs `node tools/build.mjs` and rebuilds the hashed CSS/JS manifest directly.


### 3.8.10.45
- Consistent 80/64/52px section rhythm and explicit light/dark card contrast.
- Active-theme sector media repair for demo pages, blogs, directories and galleries.
- Top-aligned About imagery plus thumbnail and modal galleries on supported directory cards and single pages.

### 3.8.10.44
- Frontend password protection is enabled by default with password `wp@demo`.
- Administrators can disable it or set a new password in **Settings → Theme Settings** at `/wp-admin/options-general.php?page=wp-theme-settings`.
- Successful visitors receive a signed access cookie valid for 24 hours by default.
- Purge full-page/server/CDN caches after changing the protection setting.

### 3.8.10.42
- Replaced demo feature icons with Tabler Icons v3.46.0 outline SVGs, sized for normal UI use and coloured from the child-theme brand token.
- Single-column imported demo rows are repaired to 12 columns at every breakpoint.
- Dark-mode demo cards use explicit dark surfaces/readable text.
- Optional frontend-only demo password protection is available in Settings → Theme Settings (default password `wp@demo`).
### 3.8.10.43
- Shared alignment and dark-mode contrast fixes across service, solution, process, directory, blog and commerce cards.
- Current child-theme media is reapplied after child-theme switches, including optimised AVIF/WebP files.
- Visible slider/grid images are loaded deterministically and duplicate single-item summary text is removed.

## 3.8.10.65 BBuilder demo system
This release expects WP BBuilder 5.6.4+ and standardises demo editing around BBuilder Row/Column, Div, Icon Card, Swiper and selected native WordPress content blocks. Legacy Group/Columns demo markup is migrated automatically.


## 3.8.11.08
WooCommerce shop, basket, checkout and account layouts were normalised across the sector suite; theme preview artwork was refreshed and package documentation was reduced to this README.


## 3.8.11.39 recovery build

This build restores the original 3.8.11.36 frontend/runtime exactly. The only release-level change is the version bump to 3.8.11.39 so WordPress can replace the broken 3.8.11.38 package cleanly. No v137/v138 frontend overrides are included.

## 3.8.11.41 - Tech Shop Automotive-reference recovery
- Ports the proven Automotive v137-v139 layout architecture without vehicle-specific content mutation.
- Retires conflicting v119-v136 frontend geometry and restores v118 as the stable base.
- Loads adapted v130/v132 commerce fixes only on WooCommerce routes.
- Aligns homepage shells to the measured header/footer content edge.
- Restores stable services, industries, process, stats, product, editorial and gallery grids.
- Adds the Automotive-style accessible hero pager and tighter homepage vertical rhythm.
- Ports deterministic shop, product, cart, checkout and account layouts.
- Repairs My Account endpoint routing and WooCommerce page ownership.
- Removes checkout marketing/newsletter fields from the transactional flow.
- Uses the Automotive cart DOM (no extra cart wrapper) for reliable two-column desktop layout.
- Preserves Tech Shop content, imagery and blue brand accent.


## 3.8.11.41 - deterministic grid + hero quality pass
- Removes the v137 DOM-shape grid detector and leaves page markup untouched.
- Uses one 1320px content edge for homepage sections and WooCommerce shells.
- Applies explicit responsive grids to services, use-cases, process, stats, categories, trust cards, catalogue and editorial sections.
- Keeps gallery/hero Swipers intact instead of converting their wrappers to CSS grids.
- Replaces all v118 hero slides with a new high-resolution photo-only Tech Shop workspace asset.
- Keeps WooCommerce shop and related-product grids at 3 / 2 / 1 columns with consistent gaps.


## 3.8.11.42
- Restores the Automotive reference's measured header-edge alignment for homepage section shells.
- Re-enables deterministic grid marking for known homepage card rows without moving, cloning or rebuilding DOM nodes.
- Retires v141's static grid/1320px owner, which did not match every rendered BBuilder wrapper.
- Restores the shared Automotive/Tech v97+v118 hero geometry while keeping the new high-resolution hero image.
- Normalises Tech-specific trust/category/partner strips to the same measured edge.
- Leaves proven v130/v132/v139 WooCommerce layouts in charge of shop, product, cart, checkout and account pages.

## 3.8.11.43
Screenshot-led recovery: tightened hero height/edge alignment, restored 2-column media-text row, fixed dark CTA contrast, upgraded gallery/product media, repaired Woo product tabs/related grid and hardened My Account routing.

## 3.8.11.44
- Uses the supplied 2026-09-17 DB backup to preserve the existing canonical front page and Woo page assignments rather than rebuilding them.
- Removes v143 homepage hero geometry and restores the stable v118 hero with measured header-edge alignment.
- Preserves the real homepage `wpbb/catalogue` output and ports the Events exact-row ownership repair (4/2/1); a deterministic PHP product grid is used only if that dynamic block fails to render cards.
- Ports the Events v139/v140 exact catalogue-row ownership and nested-grid cleanup so wrapper grids cannot collapse product cards into vertical slivers.
- Keeps the homepage media/text row, promo panel and gallery aligned without moving DOM nodes.
- Locks the DB-backed services, industries, cases, process, stats, category, trust and Latest Thinking rows to their intended responsive grids.
- Normalises Shop, single product, related products, cart, checkout and My Account to the Events-style 1180px commerce rail.
- Reasserts `/my-account/` and endpoint rewrites from the existing DB page, and refreshes corrected demo product media under a new release key.

## 3.8.11.48
Front-page layout recovery based on the stored DB block structure: one 1180px content rail, deterministic semantic grids, contained hero, and no v142 heuristic grid marker on the homepage. WooCommerce v144 rendering/account fixes remain unchanged.
