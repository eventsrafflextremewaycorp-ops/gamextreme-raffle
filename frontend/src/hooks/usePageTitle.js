import { useEffect } from "react";

const DEFAULT_TITLE = "GameXtreme — iPhone 18 Pro & Pro Max Promo";

/** Sets the browser tab title for the current page ("Prizes · GameXtreme"). */
export function usePageTitle(title) {
  useEffect(() => {
    document.title = title ? `${title} · GameXtreme` : DEFAULT_TITLE;
  }, [title]);
}
