# UnelmaPay Documentation

This directory contains the source files for the UnelmaPay API documentation, which is built using [MkDocs](https://www.mkdocs.org/) with the [Material for MkDocs](https://squidfunk.github.io/mkdocs-material/) theme.

## Prerequisites

- Python 3.6+
- pip (Python package manager)

## Local Development

1. **Install MkDocs and required plugins**
   ```bash
   pip install mkdocs mkdocs-material mkdocs-minify-plugin
   ```

2. **Serve the documentation locally**
   ```bash
   mkdocs serve
   ```
   The documentation will be available at http://127.0.0.1:8000/

3. **Make changes**
   - Edit the Markdown files in the `docs/` directory
   - The site will automatically reload when you save changes

## Building for Production

To build the static site for production:

```bash
mkdocs build --clean
```

The built files will be in the `site/` directory.

## Deployment

### Option 1: GitHub Pages (Recommended)

1. Install the required plugin:
   ```bash
   pip install mkdocs-gh-deploy
   ```

2. Deploy to GitHub Pages:
   ```bash
   mkdocs gh-deploy
   ```

### Option 2: Manual Deployment

1. Build the site:
   ```bash
   mkdocs build --clean
   ```

2. Upload the contents of the `site/` directory to your web server.

## Documentation Structure

- `docs/` - Source Markdown files
  - `index.md` - Main landing page
  - `getting-started/` - Getting started guides
  - `api/` - API reference documentation
  - `guides/` - How-to guides and tutorials
  - `faq.md` - Frequently asked questions
  - `support.md` - Support information

## Customization

Edit `mkdocs.yml` to customize:
- Site name and metadata
- Navigation structure
- Theme options
- Plugins and extensions

## Adding New Pages

1. Create a new Markdown file in the appropriate directory
2. Add the page to the navigation in `mkdocs.yml`
3. Use the following frontmatter for better search indexing:
   ```yaml
   ---
   title: Page Title
   description: Brief description for search engines
   ---
   ```

## Testing

Before deploying, test the documentation:

```bash
mkdocs build --strict
```

This will catch any broken links or other issues.

## License

This documentation is part of the UnelmaPay project and is licensed under the [MIT License](LICENSE).
