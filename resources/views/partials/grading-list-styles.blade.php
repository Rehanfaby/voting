<style>
.mg-awaiting { padding: 8px 0 28px; }
.mg-awaiting__hero {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
    background: linear-gradient(135deg, #0a2350 0%, #1d4ed8 100%);
    color: #fff; border-radius: 18px; padding: 20px 18px; margin-bottom: 16px;
    box-shadow: 0 12px 30px rgba(10,35,80,.22);
}
.mg-awaiting__eyebrow { margin: 0 0 4px; font-size: 12px; letter-spacing: .06em; text-transform: uppercase; color: #f5c518; font-weight: 700; }
.mg-awaiting__title { margin: 0; font-size: 1.45rem; font-weight: 800; line-height: 1.2; }
.mg-awaiting__sub { margin: 8px 0 0; font-size: 13px; color: rgba(255,255,255,.82); max-width: 36rem; }
.mg-awaiting__count {
    flex-shrink: 0; min-width: 88px; text-align: center; background: rgba(255,255,255,.12);
    border: 1px solid rgba(245,197,24,.45); border-radius: 14px; padding: 10px 12px;
}
.mg-awaiting__count-num { display: block; font-size: 1.8rem; font-weight: 800; color: #f5c518; line-height: 1; }
.mg-awaiting__count-label { display: block; font-size: 11px; margin-top: 4px; color: rgba(255,255,255,.85); }
.mg-awaiting__toolbar { display: flex; gap: 10px; align-items: center; margin-bottom: 14px; flex-wrap: wrap; }
.mg-awaiting__search {
    flex: 1 1 220px; display: flex; align-items: center; gap: 10px;
    background: #fff; border: 1px solid #e2e8f0; border-radius: 999px; padding: 10px 14px;
    box-shadow: 0 4px 14px rgba(15,23,42,.05);
}
.mg-awaiting__search i { color: #64748b; }
.mg-awaiting__search input {
    border: 0; outline: 0; width: 100%; font-size: 15px; background: transparent; color: #0a2350;
}
.mg-awaiting__help-link {
    display: inline-flex; align-items: center; gap: 6px; padding: 10px 14px; border-radius: 999px;
    background: #0a2350; color: #fff !important; font-weight: 700; font-size: 13px; text-decoration: none !important;
}
.mg-awaiting__help-link--ghost {
    background: #fff; color: #0a2350 !important; border: 1px solid #cbd5e1;
}
button.mg-awaiting__help-link { border: 0; cursor: pointer; font-family: inherit; }
.mg-awaiting__help-link--danger { background: #dc2626; }
.mg-list-card__check {
    flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    width: 28px; margin: 0; cursor: pointer;
}
.mg-list-card__check input { width: 18px; height: 18px; margin: 0; cursor: pointer; }
.mg-awaiting__grid { display: grid; grid-template-columns: 1fr; gap: 10px; }
.mg-awaiting-card, .mg-list-card {
    display: flex; align-items: center; gap: 10px; padding: 12px;
    background: #fff; border: 1px solid #e7edf5; border-radius: 16px;
    box-shadow: 0 6px 18px rgba(15,23,42,.05);
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
}
.mg-list-card { align-items: flex-start; }
.mg-list-card:hover {
    transform: translateY(-1px); border-color: #f5c518;
    box-shadow: 0 10px 24px rgba(10,35,80,.12);
}
.mg-list-card__main {
    display: flex; align-items: flex-start; gap: 12px; flex: 1 1 auto; min-width: 0;
    text-decoration: none !important; color: inherit;
}
.mg-awaiting-card__photo {
    width: 64px; height: 64px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
    background: #0a2350; border: 3px solid #f5c518;
    display: flex; align-items: center; justify-content: center;
}
.mg-awaiting-card__photo img { width: 100%; height: 100%; object-fit: cover; }
.mg-awaiting-card__initial { color: #f5c518; font-weight: 800; font-size: 1.4rem; }
.mg-awaiting-card__body { flex: 1 1 auto; min-width: 10rem; }
.mg-awaiting-card__name {
    margin: 0; font-size: 15px; font-weight: 800; color: #0a2350;
    line-height: 1.35; white-space: normal; overflow-wrap: break-word; word-break: normal;
}
.mg-awaiting-card__hint { margin: 4px 0 0; font-size: 12px; color: #64748b; overflow-wrap: break-word; }
.mg-list-card__bar {
    margin-top: 8px; height: 7px; border-radius: 999px; background: #e8edf5; overflow: hidden;
}
.mg-list-card__bar-fill { height: 100%; border-radius: 999px; min-width: 0; }
.mg-list-card__score {
    flex-shrink: 0; text-align: right; font-weight: 800; line-height: 1.1; min-width: 52px;
}
.mg-list-card__score strong { display: block; font-size: 1.35rem; }
.mg-list-card__score small { font-size: 12px; color: #94a3b8; font-weight: 700; }
.mg-list-card__actions {
    display: flex; flex-direction: column; gap: 6px; flex-shrink: 0;
}
.mg-list-card__actions form { margin: 0; }
.mg-list-card__btn {
    width: 36px; height: 36px; border-radius: 10px; border: 0;
    display: inline-flex; align-items: center; justify-content: center;
    background: #0a2350; color: #fff !important; text-decoration: none !important;
    cursor: pointer;
}
.mg-list-card__btn--danger { background: #dc2626; }
.mg-awaiting__empty {
    text-align: center; background: #fff; border-radius: 18px; padding: 36px 18px;
    border: 1px solid #e7edf5;
}
.mg-awaiting__empty i { font-size: 42px; color: #f5c518; }
.mg-awaiting__empty h3 { margin: 12px 0 6px; color: #0a2350; font-weight: 800; }
.mg-awaiting__empty p { color: #64748b; margin-bottom: 16px; }
.mg-awaiting__none-match { text-align: center; color: #64748b; margin-top: 18px; }
@media (min-width: 768px) {
    .mg-awaiting__title { font-size: 1.75rem; }
    .mg-awaiting-card__photo { width: 72px; height: 72px; }
}
@media (min-width: 1100px) {
    .mg-awaiting__grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 575.98px) {
    .mg-awaiting { overflow-x: hidden; }
    .mg-awaiting__hero { padding: 12px; border-radius: 14px; }
    .mg-awaiting__title { font-size: 1.15rem; }
    .mg-awaiting__sub { font-size: 12px; }
    .mg-awaiting__toolbar { gap: 8px; }
    .mg-awaiting-card, .mg-list-card { width: 100%; max-width: 100%; }
    .mg-list-card__score strong { font-size: 1.15rem; }
}
</style>
