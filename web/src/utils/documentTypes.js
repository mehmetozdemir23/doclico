export const TYPE_META = {
  facture:     { label: "Facture",       cls: "bg-blue-50 text-blue-600" },
  devis:       { label: "Devis",         cls: "bg-cyan-50 text-cyan-600" },
  avoir:       { label: "Avoir",         cls: "bg-amber-50 text-amber-600" },
  prestation:  { label: "Prestation",    cls: "bg-purple-50 text-purple-600" },
  reclamation: { label: "Relance",       cls: "bg-rose-50 text-rose-600" },
  note_frais:  { label: "Note de frais", cls: "bg-green-50 text-green-600" },
};

export const typeLabel = (type) => TYPE_META[type]?.label ?? type ?? "—";
export const typeClass = (type) => TYPE_META[type]?.cls ?? "bg-slate-100 text-slate-500";

export const NON_DELETABLE_TYPES = ['facture', 'avoir', 'note_frais', 'prestation'];
