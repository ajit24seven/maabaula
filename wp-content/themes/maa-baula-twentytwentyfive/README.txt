Maa Baula Twenty Twenty-Five Theme
==================================

This is a standalone WordPress block theme. It does not require the official
Twenty Twenty-Five theme or any other parent theme.

INSTALLATION
------------
1. Go to Appearance > Themes > Add New Theme > Upload Theme.
2. Upload maa-baula-twentytwentyfive.zip and select Install Now.
3. Activate "Maa Baula Twenty Twenty-Five".
4. Open Settings > Permalinks and click Save Changes if service URLs show 404.

Create and manage service entries under Services in the dashboard. The service
cards use each published service's title, excerpt and featured image; add or
edit those fields in the service entry to update the cards. The theme does not
create or overwrite service content.
Edit the Industries page under Pages to manage its cards in the full-width
Industry Supports panel below the editor. Choose an image, enter its title and
description, and select Add New Support to add another card. Save the page to
publish changes. Existing industry cards are imported automatically.
Edit the Material Supply page under Pages to manage its supply categories in the
Material Supply Categories panel below the editor. Update each title and
description or use Add New Material to add another row. Use Material Supply
Introduction in the same panel to update the section heading, introductory
copy, button, and image.
Edit the Infrastructure page under Pages to update the section image, alt text,
eyebrow, heading, description, project support items, and link in the
Infrastructure Section panel below the editor. Use Add New Support Item to add
another item, then save the page to publish changes.
The homepage is stored as editable Gutenberg blocks on the Home page. If a
static homepage has not been selected, the theme creates Home and selects it
once. Each homepage section is also stored on its own published page and those
pages' content is included below the Home page banner. Edit a section in Pages
to update it both on the homepage and its standalone page. The service grid
and banner carousel use published Services entries. The starter homepage
pattern is assembled from individual HTML files in
patterns/homepage-sections, one file per section.

The homepage banner's vertical service carousel is provided by the automatically
loaded Maa Baula Services Banner Carousel must-use plugin in wp-content/mu-plugins.
It shows three service thumbnails at a time and rotates through up to the first
six published services. Update each service's featured image, excerpt and title
in Services to change its carousel items.

SITE EDITOR
-----------
The theme uses WordPress block themes, the block editor and Site Editor. Its
header, footer and templates are included with the theme. The front-page
template renders the selected Home page's block content. Service entries
remain editable as WordPress content.

If a previous Site Editor customization still shows the old layout, reset the
overridden template to the theme default and clear any page/cache plugin cache
after updating the theme.

THEME CODE ORGANIZATION
-----------------------
functions.php is the theme bootstrap. Feature code is grouped under includes:
content types and setup, section shortcodes, service and homepage behavior,
navigation, and presentation shortcodes. Page editor fields, saving, and their
assets are in includes/admin. Content migrations are in includes/migrations.
Shared editor controls, including image selection and repeatable rows, are in
assets/js/section-fields.js. Use the section-title CSS class for shared
responsive typography on section headings.

LOGO AND NAVIGATION
-------------------
The supplied MB logo crop is included. The header and footer links are built
from published pages, excluding the selected homepage. Create or publish a
page to add it to the header navigation, rename it to update its label, and set
its Order under Page Attributes to control its position. Generated homepage
section pages continue linking to their sections on the homepage.

ENQUIRY FORM
------------
The form validates fields on the server and sends enquiries to
info@maabaula.com with WordPress wp_mail(). Configure SMTP or another reliable
mail transport with the hosting provider if mail is not delivered.

IMAGES AND FONTS
----------------
Industrial photographs and Google Fonts are loaded from external providers.
An internet connection is required. Replace image URLs with locally licensed
assets if required by the deployment environment.
