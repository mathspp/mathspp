---
author: Michal Dorner
date: 12-11-2025 14:19
link: https://github.com/dorny/paths-filter
taxonomy:
    category: link
title: "GitHub - dorny/paths-filter: Conditionally run actions based on files modified by PR, feature branch or pushed commits"
# via:
---

Awesome GitHub action that lets you run other action jobs/steps only if certain files have been modified.

Specifically, I use this to deploy only the Cloudflare worker(s) that were changed by a commit when I push to the [main branch](https://github.com/mathspp/tools) of [my tools subdomain](https://tools.mathspp.com).

Here's what my action workflow file looks like:

```yaml
name: Deploy Cloudflare Workers

on:
  push:
    branches:
      - main
    paths:
      - 'cloudflare-workers/**'
  workflow_dispatch: # Allow manual triggering

jobs:
  changes: # Use https://github.com/dorny/paths-filter to check what workers were changed
    name: Detect Cloudflare worker changes
    runs-on: ubuntu-latest
    permissions:
      pull-requests: read
    outputs:
      gumroad_products: ${{ steps.filter.outputs.gumroad-products }}
      # (2) If I add more filters below (1), I need to “export them” here.
    steps:
    - uses: actions/checkout@v4
      with:
        fetch-depth: 0
    - uses: dorny/paths-filter@v3
      id: filter
      with:
        filters: |
          gumroad-products:
            - 'cloudflare-workers/gumroad-products/**'
        # (1) I can add more filters here and access them separately.

  deploy-gumroad-products:
    name: Deploy Gumroad Products Worker
    runs-on: ubuntu-latest
    needs: changes
    if: ${{ github.event_name == 'workflow_dispatch' || needs.changes.outputs.gumroad_products == 'true' }}
    steps:
      ...  # Steps to deploy this specific worker.
```
