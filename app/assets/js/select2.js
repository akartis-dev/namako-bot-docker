import "../../node_modules/select2/dist/js/select2.full.min";

function matchCustom(params, data) {
  if ($.trim(params.term) === "") {
    return data;
  }

  if (typeof data.children === "undefined") {
    return null;
  }

  const filteredChildren = [];
  $.each(data.children, function (idx, child) {
    if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
      filteredChildren.push(child);
    }
  });

  return filteredChildren;
}

$(document).ready(function () {
  $("select").select2({
    placeholder: "Selectionner un ou plusieurs utilisateur",
    matcher: matchCustom,
  });
});
