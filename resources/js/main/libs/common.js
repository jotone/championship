export const initCKE = wrap => CKEDITOR.replace(wrap[0], {
  language: 'uk',
  removePlugins: 'sourcearea',
  // Define the toolbar groups as it is a more accessible solution.
  toolbarGroups: [
    {
      "name": "basicstyles",
      "groups": ["basicstyles"]
    },
    {
      "name": "paragraph",
      "groups": ["list", "blocks"]
    },
    {
      "name": "styles",
      "groups": ["styles"]
    }
  ],
  // Remove the redundant buttons from toolbar groups defined above.
  removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
})