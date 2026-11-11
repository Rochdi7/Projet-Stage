# Backoffice Dashboard: Design Report

Three high-fidelity, working maquettes of the **gestion location** backoffice, built on the real modules, statuses and workflows of this Laravel app. Each one is captured with Playwright on desktop (1440 px) and mobile (390 px).

> **Scope:** the internal backoffice only. No public website, no landing pages.
> **Branding:** all maquettes are unbranded. They have no logo and no logo placeholder, and the identity is the plain text "Gestion Location". The production app was not touched.
> **Isolation:** everything lives in `docs/design-research/dashboard/`. Nothing was committed.

**Quick start:** open [index.html](index.html) to compare the three concepts side by side. Each prototype is a single HTML file you can open directly in a browser; no build step is needed.

| | Prototype | Screenshots |
|---|---|---|
| A: Minimal Premium | [prototypes/concept-a-minimal/index.html](prototypes/concept-a-minimal/index.html) | `generated/concept-a-minimal-premium--*.png` |
| B: Modern SaaS | [prototypes/concept-b-saas/index.html](prototypes/concept-b-saas/index.html) | `generated/concept-b-modern-saas--*.png` |
| C: Enterprise | [prototypes/concept-c-enterprise/index.html](prototypes/concept-c-enterprise/index.html) | `generated/concept-c-enterprise--*.png` |

**Recommendation:** **Concept B (Modern SaaS)** as the base. Borrow Concept C's contracts toolbar and preview pane, and Concept A's typographic restraint. See [§5](#5-recommendation).

---

## 1. The existing backoffice

### 1.1 Stack and purpose

- **Backend:** Laravel 12 with PHP 8.2 and Blade views. Spatie Permission handles roles and permissions, Spatie Media Library handles files, and DomPDF produces contracts and invoices.
- **Frontend:** a purchased Bootstrap 5 admin template, with Tabler Icons, DataTables and daterangepicker. Four chart libraries are loaded on every page: ApexCharts, Chart.js, C3 and D3. The layout is [layout/mainlayout_admin.blade.php](../../../resources/views/layout/mainlayout_admin.blade.php) and the menu is [sidebar_admin.blade.php](../../../resources/views/layout/partials/sidebar_admin.blade.php).
- **Product:** a multi-agency car-rental management SaaS for Moroccan agencies, priced in MAD with a French-speaking staff. It is multi-tenant, with agencies, subscriptions and a super-admin.
- **Users:**
  - Agents create contracts and bookings and record payments all day.
  - Managers (gérants) watch revenue, the fleet and compliance.
  - Accountants work on invoices and finance.
  - A super-admin manages agencies and subscriptions.

### 1.2 Modules found (permission-gated sidebar)

| Group | Modules (routes `backoffice.*`) |
|---|---|
| Pilotage | Dashboard (date-range filter, summary cards, charts), Statistiques (revenue, bookings, vehicles, customers), reports and exports (PDF/Excel) |
| Agences | Agencies, Agency subscriptions, My subscription |
| Accès / Équipe | Roles & permissions, Agents, Users |
| Clients | Clients, Blacklist (with count badge), duplicate and blacklist checks |
| Suivi véhicule | Vignettes, Assurances, Vidanges, Contrôle technique, Contrôles and control items |
| Contrats | Rental contracts (PDF, convert from booking, signatures), Contract clients |
| Véhicules | Cars, Crédits (payment schedule, late-payment badge), Brands, Models |
| Réservations | Bookings, Booking calendar |
| Finance | Accounts, Categories, Transactions |
| Facturation / Paiements | Invoices (PDF, settings, template), Invoice items, Payments |
| Système | Notifications (active, unread, archived), Profile, Settings, Trash (restore, force delete) |

Statuses come from the models. For example, `RentalContract` uses Brouillon, En attente, Accepté, En cours, Terminé and Annulé, and `Vehicle` uses available, unavailable, maintenance and sold. The maquettes reuse these exact labels.

### 1.3 Current screens (captured via the demo login, logo hidden)

| Dashboard | Contracts | Vehicles | Mobile |
|---|---|---|---|
| ![](current/current-dashboard-desktop.png) | ![](current/current-rental-contracts-desktop.png) | ![](current/current-vehicles-desktop.png) | ![](current/current-rental-contracts-mobile.png) |

All 17 captures are in [current/](current/), covering 14 pages on desktop plus 3 on mobile.

### 1.4 What to improve

1. **Navigation overload.** There are 13 section titles and about 30 links in one long column, and some section titles are cut off ("ÉHICULE SUIVI", "OYEE"). There is no grouping by job (daily activity, fleet, money, administration).
2. **Mixed languages and currencies.** The dashboard is in English ("Total No of Cars", "Newly Added Cars") while the modules are in French. The dashboard shows `$` and the modules show `MAD`.
3. **A dashboard that doesn't drive action.** The welcome banner and "Total Cars" appear twice. Nothing surfaces what needs doing today: departures and returns, bookings to confirm, overdue payments, or expiring insurance and oil changes. Yet the app already has that data in its vehicle-document modules and notifications.
4. **Competing colours.** The primary is orange, Export is a saturated red, the top button is black, and the vehicles page has four loud full-colour KPI cards. Nothing establishes which action matters most.
5. **Tables.** The toolbar mixes sort, filter, export, search and create at the same visual weight. There are no status tabs with counts, no active-filter tokens, no bulk bar and no inline payment progress.
6. **Mobile.** Tables overflow sideways, the toolbar stacks into five full-width rows, and the footer pushes content down.
7. **Template leftovers.** Template page titles, the Privacy/Terms footer and four chart libraries remain. Consolidating to one chart library would save weight and keep charts consistent.

---

## 2. Design-language influences

At your request, no external reference screenshots were captured; the concepts are built from this project's own screens and data. The table names the design systems each concept learns from.

| # | Influence | What it informed |
|---|---|---|
| 1 | Linear | Quiet dark sidebar, keyboard-first (⌘K / `/` / `N`), density without noise (B, C) |
| 2 | Stripe Dashboard | Hero revenue figure with a thin trend line, quiet KPI strip, payment timelines (A, B) |
| 3 | Vercel Dashboard | Monochrome primary, hairline borders, segmented period control (A) |
| 4 | Notion | Detail page laid out like a document, calm typography (A) |
| 5 | Attio | Record header with key facts strip, tabs, right rail (B, C) |
| 6 | Supabase | Dense data tables, filter tokens, column chooser (C) |
| 7 | Raycast | Command palette that filters pages and actions (B) |
| 8 | shadcn/ui and Tailwind-style admin kits | Status pills, card radius, spacing scale (B) |
| 9 | Salesforce / SAP Fiori-type enterprise apps | Global context bar (agency and period), work queues, split view, status bar (C) |
| 10 | Bootstrap admin templates (the current base) | Kept the familiar left-nav and card mental model so the migration stays incremental (all) |

---

## 3. The three concepts

All three share the same data ([shared/data.js](prototypes/shared/data.js)): an agency of 42 vehicles in Casablanca, MAD amounts, French labels, and the real statuses. They also share a small dependency-free SVG chart helper ([shared/charts.js](prototypes/shared/charts.js)) with hover tooltips, rounded bar ends and resize handling.

Each prototype implements the same screens and states:

- **Screens:** overview, analytics, contracts list, contract detail (CTR-2026-0413), new contract form, team and roles, settings with the notification centre.
- **States:** a modal, the notifications dropdown, and the mobile navigation drawer.
- **Sidebar:** other sidebar items open an "écran hors maquette" placeholder rather than inventing screens.

**Working interactions in all three:** hash-routed sidebar with collapsible groups; search; status tabs with counts; sorting; select-all with a bulk action bar; row action menus; filter modal; notifications and user dropdowns; "Enregistrer un paiement" modal; toasts; Esc and backdrop closing.

The new-contract form has client search, a **blacklist block** (pick "Mehdi Alaoui"), a vehicle picker limited to available cars, a live price summary (days × rate − discount), validation and a success toast.

### Concept A: Minimal Premium

Calm and editorial: warm neutrals, hairline borders, one large revenue figure, Instrument Serif for titles and Geist for the UI. The primary action is ink black, so colour is reserved for data and status. It also has a dark theme (user menu → Thème).

| Overview | Analytics | Contracts |
|---|---|---|
| ![](generated/concept-a-minimal-premium--01-overview-desktop.png) | ![](generated/concept-a-minimal-premium--03-analytics-desktop.png) | ![](generated/concept-a-minimal-premium--04-listing-contracts-desktop.png) |
| **Detail** | **Form** | **Mobile** |
| ![](generated/concept-a-minimal-premium--05-detail-contract-desktop.png) | ![](generated/concept-a-minimal-premium--06-form-new-contract-desktop.png) | ![](generated/concept-a-minimal-premium--02-overview-mobile.png) |

**Strengths:** the most elegant of the three, with the lowest cognitive load, and the best fit for managers and owners.
**Weaknesses:** lower density for agents handling many contracts. A monochrome primary makes "create" and "pay" less prominent. The serif titles are a brand-like choice that should be validated.

### Concept B: Modern SaaS

A dark navy sidebar with an indigo primary (`#4F46E5`), Plus Jakarta Sans for the UI and JetBrains Mono for IDs and plates. It adds:

- KPI cards with sparklines and change pills, plus a revenue and expenses chart that switches between Revenus and Contrats.
- A ⌘K command palette and a period control (7 j / 30 j / 12 mois).
- A contract detail page with a rental progress bar ("Jour 8 sur 14") and tabs: Aperçu, Paiements, Check-list, Historique.

| Overview | Analytics | Contracts |
|---|---|---|
| ![](generated/concept-b-modern-saas--01-overview-desktop.png) | ![](generated/concept-b-modern-saas--03-analytics-desktop.png) | ![](generated/concept-b-modern-saas--04-listing-contracts-desktop.png) |
| **Detail** | **Form** | **Mobile** |
| ![](generated/concept-b-modern-saas--05-detail-contract-desktop.png) | ![](generated/concept-b-modern-saas--06-form-new-contract-desktop.png) | ![](generated/concept-b-modern-saas--11-listing-contracts-mobile.png) |

**Strengths:** the best balance of polish, density and clear actions. Mobile cards are excellent. The structure maps directly onto Bootstrap 5 with CSS variables.
**Weaknesses:** it can look generic if overdone. On short screens, the last sidebar item sits under the user card and needs a scroll.

### Concept C: Enterprise

A navy global header with an **agency switcher and period picker**, IBM Plex Sans at a 13 px base with Plex Mono for figures, and 36 px table rows. It adds:

- An 8-tile KPI strip, **work queues** (bookings to confirm, contracts awaiting acceptance, returns within 48 h, overdue payments), a compliance table with severity, and a per-agency comparison.
- A contracts toolbar with **removable filter tokens**, saved views, a column chooser and a **split-view preview pane**.
- A status bar with keyboard shortcuts.

| Overview | Analytics | Contracts (split view) |
|---|---|---|
| ![](generated/concept-c-enterprise--01-overview-desktop.png) | ![](generated/concept-c-enterprise--03-analytics-desktop.png) | ![](generated/concept-c-enterprise--04-listing-contracts-desktop.png) |
| **Detail** | **Form** | **Mobile** |
| ![](generated/concept-c-enterprise--05-detail-contract-desktop.png) | ![](generated/concept-c-enterprise--06-form-new-contract-desktop.png) | ![](generated/concept-c-enterprise--11-listing-contracts-mobile.png) |

**Strengths:** the fastest for high-volume agents and the best for super-admins running several agencies. Work queues turn the dashboard into a to-do list.
**Weaknesses:** the heaviest to build. The mobile overview is very long (about 5 900 px) because it keeps every module. It is dense for occasional users.

### Full screenshot set (13 per concept, 39 in total)

| # | Shot | A | B | C |
|---|---|---|---|---|
| 01 | Overview (desktop) | [png](generated/concept-a-minimal-premium--01-overview-desktop.png) | [png](generated/concept-b-modern-saas--01-overview-desktop.png) | [png](generated/concept-c-enterprise--01-overview-desktop.png) |
| 02 | Overview (mobile) | [png](generated/concept-a-minimal-premium--02-overview-mobile.png) | [png](generated/concept-b-modern-saas--02-overview-mobile.png) | [png](generated/concept-c-enterprise--02-overview-mobile.png) |
| 03 | Analytics | [png](generated/concept-a-minimal-premium--03-analytics-desktop.png) | [png](generated/concept-b-modern-saas--03-analytics-desktop.png) | [png](generated/concept-c-enterprise--03-analytics-desktop.png) |
| 04 | Contracts list | [png](generated/concept-a-minimal-premium--04-listing-contracts-desktop.png) | [png](generated/concept-b-modern-saas--04-listing-contracts-desktop.png) | [png](generated/concept-c-enterprise--04-listing-contracts-desktop.png) |
| 05 | Contract detail | [png](generated/concept-a-minimal-premium--05-detail-contract-desktop.png) | [png](generated/concept-b-modern-saas--05-detail-contract-desktop.png) | [png](generated/concept-c-enterprise--05-detail-contract-desktop.png) |
| 06 | New contract form | [png](generated/concept-a-minimal-premium--06-form-new-contract-desktop.png) | [png](generated/concept-b-modern-saas--06-form-new-contract-desktop.png) | [png](generated/concept-c-enterprise--06-form-new-contract-desktop.png) |
| 07 | Team & roles | [png](generated/concept-a-minimal-premium--07-users-roles-desktop.png) | [png](generated/concept-b-modern-saas--07-users-roles-desktop.png) | [png](generated/concept-c-enterprise--07-users-roles-desktop.png) |
| 08 | Settings & notifications | [png](generated/concept-a-minimal-premium--08-settings-notifications-desktop.png) | [png](generated/concept-b-modern-saas--08-settings-notifications-desktop.png) | [png](generated/concept-c-enterprise--08-settings-notifications-desktop.png) |
| 09 | Modal | [png](generated/concept-a-minimal-premium--09-modal-desktop.png) | [png](generated/concept-b-modern-saas--09-modal-desktop.png) | [png](generated/concept-c-enterprise--09-modal-desktop.png) |
| 10 | Notifications dropdown | [png](generated/concept-a-minimal-premium--10-notifications-dropdown-desktop.png) | [png](generated/concept-b-modern-saas--10-notifications-dropdown-desktop.png) | [png](generated/concept-c-enterprise--10-notifications-dropdown-desktop.png) |
| 11 | Contracts (mobile) | [png](generated/concept-a-minimal-premium--11-listing-contracts-mobile.png) | [png](generated/concept-b-modern-saas--11-listing-contracts-mobile.png) | [png](generated/concept-c-enterprise--11-listing-contracts-mobile.png) |
| 12 | Nav drawer (mobile) | [png](generated/concept-a-minimal-premium--12-menu-drawer-mobile.png) | [png](generated/concept-b-modern-saas--12-menu-drawer-mobile.png) | [png](generated/concept-c-enterprise--12-menu-drawer-mobile.png) |
| 13 | Detail (mobile) | [png](generated/concept-a-minimal-premium--13-detail-contract-mobile.png) | [png](generated/concept-b-modern-saas--13-detail-contract-mobile.png) | [png](generated/concept-c-enterprise--13-detail-contract-mobile.png) |

**Verification:** `scripts/capture_prototypes.py` loads each prototype, renders every route and state, and records console and page errors. On the final run, all 39 shots succeeded with 0 errors; the log is in `generated/_capture-log.json`.

---

## 4. Comparison

Scores run from 1 (weak) to 5 (strong).

| Criterion | A: Minimal | B: SaaS | C: Enterprise |
|---|:---:|:---:|:---:|
| Visual polish / premium feel | **5** | **5** | 4 |
| Information density (agent productivity) | 3 | 4 | **5** |
| Clarity of the primary action (create, pay) | 3 | **5** | 4 |
| Daily "what to do" guidance | 4 | 4 | **5** |
| Multi-agency / super-admin fit | 3 | 4 | **5** |
| Mobile experience | 4 | **5** | 3 |
| Learnability for new agents | **5** | 4 | 3 |
| Implementation effort on Bootstrap 5 + Blade (5 = easiest) | 4 | **4** | 2 |
| **Total (/40)** | 31 | **35** | 31 |

---

## 5. Recommendation

**Implement Concept B (Modern SaaS)** and bring in three patterns from the others:

1. **From C:** the contracts toolbar (filter tokens, saved views, column chooser) and the **preview pane**, available on contracts, bookings and payments. Also C's **work-queue tabs** on the dashboard. These are the biggest wins for agents.
2. **From C:** the **agency switcher** in the top bar for super-admins and multi-agency managers, shown only with the right permission.
3. **From A:** the restrained **KPI strip** and quieter borders, so B never gets noisy. Optionally, A's dark theme tokens.

**Why B:**

- It fixes every issue in §1.4: action-led navigation, one primary colour, French and MAD everywhere, a "today" view, real mobile cards.
- It suits both audiences, the agent and the manager.
- It can be built incrementally on the current Bootstrap 5 + Blade + Tabler Icons stack, one layout and component set at a time, without a frontend rewrite.

---

## 6. Design system (recommended direction)

### 6.1 Colour palette

| Token | HEX | Use |
|---|---|---|
| `--sidebar-bg` | `#0E1222` | Sidebar |
| `--sidebar-raised` | `#1C2238` | Active nav item, inputs in the sidebar |
| `--sidebar-text` / `--sidebar-muted` | `#A9B0C7` / `#5D6585` | Nav labels / section titles |
| `--bg` | `#F5F6FA` | App background |
| `--surface` | `#FFFFFF` | Cards, tables, modals |
| `--border` / `--border-soft` | `#E6E8F0` / `#EEF0F5` | Card and table borders / row dividers |
| `--text` / `--text-2` | `#0F1222` / `#344054` | Primary / secondary text |
| `--muted` / `--faint` | `#667085` / `#98A2B3` | Labels / placeholders |
| `--primary` / `--primary-hover` / `--primary-tint` | `#4F46E5` / `#4338CA` / `#EEF0FF` | Primary buttons, links, focus, selected states |
| Chart series 1–4 (fixed order) | `#2A78D6`, `#EB6834`, `#1BAF7A`, `#EDA100` | Revenue, expenses, and the 3rd and 4th categories (validated for colour-blind safety) |
| Status good / warning / serious / critical | `#0CA30C` / `#FAB219` / `#EC835A` / `#D03B3B` | Always paired with an icon and a text label |

Status pills map from the model statuses:

| Model statuses | Pill tone |
|---|---|
| Brouillon | neutral |
| En attente | warning |
| Accepté | info |
| En cours | primary |
| Terminé | good |
| Annulé, Blacklisté, En retard | critical |

### 6.2 Typography

- **UI:** Plus Jakarta Sans, with fallbacks `system-ui, Segoe UI, Roboto`. Weights 400, 500, 600 and 700.
- **Data:** JetBrains Mono for contract numbers, plates, CIN numbers and references. Use `font-variant-numeric: tabular-nums` on all amounts.
- **Scale:**

| Element | Size |
|---|---|
| Page title | 24/32 semibold |
| Card title | 15/22 semibold |
| Body | 14/20 |
| Table | 13.5/20 |
| Label | 12/16 medium |
| KPI value | 28/34 bold |

- **Language:** French only in the UI. Replace "Cars", "Car Attributes", "Total No of Cars" and similar labels. Format amounts as `64 850 MAD` and dates as `jj/mm/aaaa`.

### 6.3 Sidebar and navigation

- Regroup the 13 sections into **5 jobs**, keeping the existing `@can` gates:

| Section | Modules |
|---|---|
| **Pilotage** | Tableau de bord, Statistiques |
| **Activité** | Réservations, Calendrier, Contrats, Clients du contrat, Clients, Blacklist |
| **Flotte** | Voitures, Suivi véhicule (Vignettes, Assurances, Vidanges, Contrôle technique, Contrôles), Crédits, Marques & modèles |
| **Finance** | Paiements, Factures, Items, Comptes, Catégories, Transactions |
| **Administration** | Équipe (Agents, Utilisateurs, Rôles), Agences, Abonnements, Corbeille, Paramètres |

- Use collapsible groups that remember their state and show **count badges** only where action is needed: Blacklist, Crédits en retard, Réservations à confirmer, alerts due.
- Put a text-only agency switcher at the top of the sidebar and the user card at the bottom. Add a collapse-to-icons mode for screens of 1280 px and below, and an off-canvas drawer below 992 px.
- **Top bar:** breadcrumbs, global search (`Ctrl K` / `/`), period control, bell (reusing the existing `/notifications/recent` endpoint and its active, unread and archived tabs), **one** primary button ("Nouveau contrat"), and the user menu.

### 6.4 Tables

- **Toolbar order:** status tabs with counts, then search, filter tokens, "+ Filtre" and "Filtres avancés", then on the right Vue, Colonnes and Exporter. "Exporter" becomes a secondary button with a PDF/Excel menu instead of a red button.
- **Rows:** 56 px comfortable or 40 px compact. The ID column uses the mono font. Show the client with their phone number and the vehicle with its plate.
- **Payment and status:** show an **encaissé progress bar** with the remaining amount, and a status pill. Put row actions in a `⋯` menu.
- **Selection and paging:** selecting rows shows a bulk bar (export, change status, delete to trash). Use server-side pagination with a page size control and "1–25 sur 418".
- **Mobile:** below 768 px, every row becomes a card with ID and status, client and vehicle, dates and amount, then the progress bar.

### 6.5 Charts

- **One library.** Use ApexCharts, which is already loaded, and remove Chart.js, C3 and D3 from the admin layout.
- **Colours:** fixed series order (§6.1). Revenue is always blue and expenses always orange.
- **Form:** 2 px lines, bars with rounded tops anchored to the baseline, light gridlines, a legend for two or more series, and a tooltip on hover. Never use two y-axes.
- **Partial months:** label the current month as month-to-date ("Nov.*") or exclude it from trends, so it doesn't read as a revenue crash.
- **Contrast:** aqua and yellow fall below 3:1 against white, so always show their values in the legend or a table toggle.

### 6.6 Components to build as Blade components

`x-kpi-card` (value, delta pill, sparkline), `x-status-pill` (model status to tone), `x-data-toolbar`, `x-filter-token`, `x-table` / `x-table-row-card`, `x-progress-paid`, `x-empty-state`, `x-modal`, `x-dropdown`, `x-toast` (replacing `_toasts`), `x-alert-row` (severity icon, label, due date), `x-record-header` (ID, status pills, actions, key facts), `x-timeline`, `x-form-section` (numbered section with a live summary aside).

### 6.7 Responsive behaviour

| Width | Behaviour |
|---|---|
| ≥ 1280 px | Full sidebar (264 px), 4-column KPIs, preview pane available |
| 992–1279 px | Sidebar collapses to icons, 2–4 KPIs, preview pane opens as an overlay |
| 768–991 px | Off-canvas drawer, 2-column KPIs, tables keep columns but hide secondary ones |
| < 768 px | Drawer, 1–2 column KPIs, tables become cards, toolbar collapses to search plus a "Filtres" sheet, sticky primary action, 16 px gutters, no horizontal scroll |

---

## 7. Files and how to re-run

```
docs/design-research/dashboard/
├── DASHBOARD-DESIGN-REPORT.md        this report
├── index.html                        side-by-side gallery of the three concepts
├── current/                          17 screenshots of the existing backoffice
├── generated/                        39 screenshots of the maquettes and _capture-log.json
├── prototypes/
│   ├── shared/data.js                mock data (real modules and statuses, MAD, French)
│   ├── shared/charts.js              dependency-free SVG charts with tooltips
│   ├── concept-a-minimal/index.html
│   ├── concept-b-saas/index.html
│   └── concept-c-enterprise/index.html
└── scripts/
    ├── capture_current.py            screenshots of the live app (demo login, logo hidden)
    └── capture_prototypes.py         screenshots of the maquettes and error log
```

Re-run the captures (Python Playwright is already installed):

```bash
cd docs/design-research/dashboard
python -I scripts/capture_prototypes.py . a b c
python -I scripts/capture_current.py current http://127.0.0.1:8000   # needs the app running
```

**Maquette limits.** The data is mock data. Agency and period pickers, and the analytics filters, change labels and show a toast but don't refilter. Pagination is visual only. Screens outside the brief show a placeholder.

`capture_current.py` uses `/login/demo`, which writes `last_login_at` and a "Connexion démo" notification to the local dev database.
