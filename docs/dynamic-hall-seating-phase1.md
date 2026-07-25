# Dynamic Hall Seating — Phase 1 Cursor Instruction

Paste this when continuing implementation or reviewing the hall seating stack.

## Architecture

```
Hall → HallLayoutVersion (draft|published)
     → HallLevel / HallSection / HallRow / HallTemplateSeat
Product (event ticket) → EventSeatMap (snapshot of published layout)
                       → EventSeatCategory + EventSeatInventory (available|held|sold|blocked)
Booking → hold (10 min) → pay → Ticket + TicketSeat (seat_location)
```

Legacy `product_seats` / `product_seat_zones` remain for older products. New events use the hall pipeline.

## Tables

- `halls`, `hall_layout_versions`, `hall_levels`, `hall_sections`, `hall_rows`, `hall_template_seats`
- `event_seat_maps`, `event_seat_categories`, `event_seat_inventory`
- `tickets.hold_token`, `ticket_seats.event_seat_inventory_id`, `ticket_seats.seat_location`

## Permissions (Spatie)

`halls-index`, `halls-edit`, `hall-layouts-publish`, `event-seat-maps-manage`

## Routes

| Method | Path | Controller |
|--------|------|------------|
| CRUD | `/admin/halls`, `/admin/halls/{id}/edit` | `HallController` |
| Editor | `/admin/halls/{hallId}/layouts/{layoutId}` | `HallLayoutController` |
| Publish/Fork | `…/publish`, `…/fork` | `HallLayoutController` |
| Attach | `/admin/products/{id}/event-seat-map` | `EventSeatMapController` |
| Inventory/ops | `…/inventory`, refund/reissue/relocate | `EventSeatMapController` |
| Public | `GET /api/ticket/{id}/seats` | `EventSeatMapController@publicSeats` |
| Holds | `POST/DELETE /api/ticket/{id}/holds…` | `EventSeatMapController` |

## Key services

- `App\Services\Halls\HallLayoutService` — draft, bulk rows (straight/curve), publish, fork
- `App\Services\Halls\EventSeatMapService` — snapshot layout → inventory
- `App\Services\Halls\SeatHoldService` — TTL holds, lockForUpdate, mark sold
- `App\Services\Halls\EventSeatOpsService` — refund, reissue QR, relocate
- `App\Services\Halls\SeatLabelGenerator` — `A-01`, `BAL-A-12`, `VIP-10`

## File touch list

- Models: `app/Hall*.php`, `app/EventSeat*.php`
- Controllers: `HallController`, `HallLayoutController`, `EventSeatMapController`, `HomeController` (resolve/reserve/release/process payment)
- Views: `resources/views/halls/*`, `frontend/ticket.blade.php`, scan blades
- Cron: `seats:release-expired-holds` every minute
- Migrations: `2026_07_25_210000_*`, `2026_07_25_210100_*`

## Acceptance checks

1. Publish layout v1 → attach to product → sell seats → fork to v2 redesign → sold seats on event map unchanged.
2. Two concurrent holds on same seat → one succeeds.
3. Abandoned checkout → seats available after 10 minutes (`seats:release-expired-holds`).
4. Category filter + server-side price sum (never trust client amount alone for MoMo/Stripe totals from inventory).
5. QR / WhatsApp / gate scan show `Hall · Level · Section · Row · Seat` via `seat_location`.
6. Legacy quantity tickets still work when `seat_selection_enabled` is off.

## Tests

```bash
./vendor/bin/phpunit --filter 'SeatLabelGeneratorTest|SeatHoldServiceTest'
```

- `tests/Unit/Halls/SeatLabelGeneratorTest.php` — label formats
- `tests/Unit/Halls/SeatHoldServiceTest.php` — double-book, expiry, price sum

## Deploy

```bash
php artisan migrate --force
php artisan permission:cache-reset  # if used
# Admin role is granted halls-* + event-seat-maps-manage by migration 2026_07_25_210100
```