/**
 * Formate une date au format court français (jj/mm/aaaa)
 * @param {Date|string} date - Date à formater
 * @returns {string} Date formatée (ex: "15/01/2026")
 */
export const formatShortDate = (date = new Date()) => {
  const dateObj = typeof date === "string" ? new Date(date) : date;
  return dateObj.toLocaleDateString("fr-FR", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
};

/**
 * Formate une date au format long français avec heure (jj mois aaaa à hh:mm)
 * @param {Date|string} dateString - Date à formater
 * @returns {string} Date formatée (ex: "15 janvier 2026 à 14:30")
 */
export const formatLongDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat("fr-FR", {
    day: "numeric",
    month: "long",
    year: "numeric",
    hour: "numeric",
    minute: "numeric",
  }).format(date);
};
