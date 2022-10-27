import { render, h } from "preact";

export const renderPreact = (Component, attribute) => {
  const elements = document.querySelectorAll(`[${attribute}]`);

  elements.forEach(function (element) {
    const data = element?.dataset;

    render(<Component {...data} />, element, element.firstChild);
  });
};
