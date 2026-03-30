# PMPro Downloads Add On — Documentation

Create restricted file downloads for your Paid Memberships Pro members. The plugin registers a Downloads content type where each post has a protected file attached. Members with the appropriate level can download the file; everyone else sees a locked state with a prompt to join.

---

## Table of Contents

- [How It Works](#how-it-works)
- [Installation](#installation)
- [Creating a Download](#creating-a-download)
- [Restricting a Download to Membership Levels](#restricting-a-download-to-membership-levels)
- [Displaying Downloads on the Frontend](#displaying-downloads-on-the-frontend)
- [Download Templates](#download-templates)
- [Download Library](#download-library)
- [Action and Filter Hooks](#action-and-filter-hooks)
- [Frequently Asked Questions](#frequently-asked-questions)

---

## How It Works

This Add On registers a **Download** custom post type (`pmpro_download`). Each Download post holds a single protected file that is stored in PMPro's restricted files directory — outside of the public WordPress uploads folder.

When a visitor requests a download, PMPro checks their membership level before serving the file. If they don't have the right level, they are shown a locked state with a call-to-action to join or upgrade.

> **Note:** Downloaded files are served through PMPro's restricted file system. Paid Memberships Pro must be installed and active for file uploads and protected delivery to work.

---

## Installation

You must have the Paid Memberships Pro plugin installed and activated with a valid license type to use this Add On.

1. Navigate to **Memberships** > **Add Ons** in the WordPress admin.
2. Locate the Add On and click **Install Now**.
   - To install manually, download the .zip file above, then upload it via **Plugins** > **Add New** > **Upload Plugin**.
3. **Activate** the plugin through the Plugins screen in the WordPress admin.

---

## Creating a Download

1. Navigate to **Downloads** in the WordPress admin sidebar.
2. Click **Add New Download**.
3. Enter a **Download Name** in the title field (this is what members will see as the download label by default).
4. In the **Download File** block on the editor canvas, click **Upload File** and select the file from your computer. The file is stored in PMPro's protected uploads directory.
5. Optionally, add a **Description** using the Description panel in the right sidebar. The description appears alongside the download in the Card template.
6. Set the membership level restriction (see below), then **Publish** the post.

### Managing the Attached File

- **Replace File**: Upload a new file. The previous file is deleted from the server after the new one is uploaded successfully.
- **Remove**: Deletes the file from the server and clears the file metadata. Members will no longer see a download link until a new file is uploaded.

---

## Restricting a Download to Membership Levels

Downloads use PMPro's standard content restriction system:

1. Open a Download in the block editor.
2. In the **Paid Memberships Pro** section of the right-hand sidebar, select which membership levels should have access.
3. If no levels are selected, the file is accessible to all logged-in users (and potentially logged-out users depending on your PMPro settings).

The **Membership** column on the **Downloads** list table shows which levels are assigned to each download at a glance.

---

## Displaying Downloads on the Frontend

There are three ways to display a download on any page, post, or widget area.

### Block Editor

Two blocks are available in the block inserter under the **PMPro** category:

- **PMPro Download** — displays a single download by ID.
- **PMPro Download Library** — displays all published downloads in a list or grid.

### Shortcodes

#### Single Download

```
[pmpro_download id="123"]
```

| Attribute  | Default      | Description |
|------------|--------------|-------------|
| `id`       | *(required)* | The post ID of the Download. Visible in the Shortcode column of the Downloads list. |
| `template` | `link`       | Display template: `link`, `card`, or `button`. |
| `label`    | `title`      | What to use as the download name: `title` (the post title) or `filename` (the uploaded filename). |

#### Download Library

```
[pmpro_download_library]
```

| Attribute  | Default | Description |
|------------|---------|-------------|
| `template` | `link`  | Display template: `link`, `card`, or `button`. |
| `layout`   | `list`  | Layout style: `list` or `grid`. |
| `columns`  | `2`     | Number of columns when `layout="grid"`. Accepts `2` or `3`. |
| `label`    | `title` | Download name label: `title` or `filename`. |
| `limit`    | `-1`    | Maximum number of downloads to show. `-1` shows all. |
| `orderby`  | `title` | Sort field: `title` or `date`. |
| `order`    | `asc`   | Sort direction: `asc` or `desc`. |

---

## Download Templates

Each display method supports three templates that control how the download appears.

### Link (`template="link"`)

A compact text link with a file-type icon. Locked downloads show a lock icon and "Requires [level] membership" note.

### Button (`template="button"`)

A prominent CTA button with a download icon, the file name, and a "Download" label. Locked downloads show a "Requires [level] membership" button linking to the checkout or levels page.

### Card (`template="card"`)

A PMPro-styled card with the download title, optional description, and an action button. Locked downloads show the description (if set), the membership requirement, and a "Join Now" or "View Membership Options" button.

### Non-member CTA

When a download is locked, the CTA button automatically links to:

- The **checkout page** for that level, if only one level has access.
- The **membership levels page**, if multiple levels have access.

---

## Download Library

The `[pmpro_download_library]` shortcode and **PMPro Download Library** block display all published Downloads. Each item in the library is rendered using the same template as a single `[pmpro_download]`.

- Members see a download link/button/card for files they can access.
- Non-members see a locked version showing what membership is required.
- Downloads with no file uploaded are not shown to members. Non-members will see the locked state for all Downloads.

---

## Action and Filter Hooks

This Add On does not currently expose public action/filter hooks. File delivery is handled by PMPro's core restricted file system. The following PMPro core filters are relevant:

```php
apply_filters( 'pmpro_can_access_restricted_file', bool $can_access, string $file_dir, string $file );
```

Determines whether the current user can access a specific restricted file. This Add On hooks into this filter to check membership access for files in the `pmpro-downloads` directory.

```php
apply_filters( 'pmpro_restrictable_post_types', array $post_types );
```

This Add On adds `pmpro_download` to the list of post types that PMPro can restrict, enabling the standard level-assignment UI in the block editor sidebar.

---

## Frequently Asked Questions

**Where are my uploaded files stored?**

Files are stored in PMPro's restricted uploads directory, which is located outside of the standard WordPress `wp-content/uploads` folder and is protected from direct browser access. Files can only be served through PMPro's authenticated download handler.

---

**What file types can I upload?**

Any file type that WordPress permits for upload on your site. The allowed file types are controlled by WordPress's standard `upload_mimes` filter, which site administrators can customize. The file is stored in a protected directory regardless of type.

---

**Can a member download a file multiple times?**

Yes. There is no download limit per user in this version. Once a member has the correct membership level, they can download the file as many times as they like.

---

**What happens when I delete a Download post?**

The physical file is permanently deleted from the server when the post is permanently deleted (not just moved to the Trash). If you want to keep the file, replace it or move it before deleting the post.

---

**What happens when I replace a file?**

The old file is deleted from the server only after the new file uploads successfully. If the new upload fails, the original file is preserved.

---

**Can I display multiple downloads on one page?**

Yes, use either the `[pmpro_download_library]` shortcode/block to show all downloads, or place multiple `[pmpro_download id="..."]` shortcodes individually on the same page.

---

**The Shortcode column in the Downloads list shows the ID. How do I find a download's ID?**

The Shortcode column on the Downloads list table shows the ready-to-copy shortcode for each download, including its ID. You can also find the ID in the URL when editing a download post (`post=123` in the URL).
