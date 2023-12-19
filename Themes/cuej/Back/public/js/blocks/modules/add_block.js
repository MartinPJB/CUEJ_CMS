/**
 * Library for handling block creation.
 * @namespace
 */
const block_create = {
  /**
   * Handles the show form function.
   * @function
   * @param {Event} e - The event triggering the displayForm function.
   */
  displayForm(e) {
    const { articleid: articleID, type: targetForm } = e.target.dataset;
    const form = window.fields[targetForm];

    if (!form || !articleID) {
      return;
    }

    const formContainer = document.querySelector("#cuej__block-creation-form");
    formContainer.innerHTML = ""; // Clear previous content
    formContainer.appendChild(this.createFormElement(articleID, targetForm, form));

    document.querySelector("#step__1").classList.toggle("hidden");
    document.querySelector("#step__2").classList.toggle("hidden");
  },

  /**
   * Creates and returns the form element.
   * @function
   * @param {string} articleID - The ID of the article.
   * @param {string} targetForm - The target form.
   * @param {Object} form - The form data.
   * @returns {HTMLFormElement} - The created form element.
   */
  createFormElement(articleID, targetForm, form) {
    const formHTML = document.createElement("form");
    formHTML.id = "cuej__block-creation-form";
    formHTML.action = `admin/create_block/${articleID}`;
    formHTML.method = "POST";

    // Add hidden input for type
    formHTML.appendChild(this.createHiddenInput("type", targetForm));

    // Add name input
    formHTML.appendChild(this.createInputLabelAndInput("Block name", "name", "text", "Block name"));

    // Add other form inputs
    for (const input in form) {
      formHTML.appendChild(this.createInputLabelAndInput(form[input].label, input, form[input].type, form[input].label, form[input].min, form[input].max));
    }

    // Add submit button
    formHTML.appendChild(this.createSubmitButton());

    return formHTML;
  },

  /**
   * Creates and returns a hidden input element.
   * @function
   * @param {string} name - The name attribute of the input.
   * @param {string} value - The value attribute of the input.
   * @returns {HTMLInputElement} - The created hidden input element.
   */
  createHiddenInput(name, value) {
    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = name;
    hiddenInput.value = value;
    return hiddenInput;
  },

  /**
   * Creates and returns a label and input element.
   * @function
   * @param {string} labelText - The text content of the label.
   * @param {string} inputID - The ID attribute of the input.
   * @param {string} inputType - The type attribute of the input.
   * @param {string} inputPlaceholder - The placeholder attribute of the input.
   * @param {number} [min] - The min attribute of the input (optional).
   * @param {number} [max] - The max attribute of the input (optional).
   * @returns {DocumentFragment} - The created label and input elements.
   */
  createInputLabelAndInput(labelText, inputID, inputType, inputPlaceholder, min, max) {
    const fragment = document.createDocumentFragment();

    // Create label
    const label = document.createElement("label");
    label.htmlFor = inputID;
    label.innerHTML = labelText;

    // Create input
    const input = inputType === "textarea" ? document.createElement("textarea") : document.createElement("input");

    // Set input type for non-textarea elements
    if (inputType !== "textarea") {
      input.type = inputType;
    }

    input.name = inputID;
    input.id = inputID;
    input.placeholder = inputPlaceholder;

    // Add min and max attributes if present
    if (min !== undefined) {
      input.min = min;
    }

    if (max !== undefined) {
      input.max = max;
    }

    // Append label and input to fragment
    fragment.appendChild(label);
    fragment.appendChild(input);

    return fragment;
  },

  /**
   * Creates and returns a submit button element.
   * @function
   * @returns {HTMLButtonElement} - The created submit button element.
   */
  createSubmitButton() {
    const submitButton = document.createElement("button");
    submitButton.type = "submit";
    submitButton.innerHTML = "Create block";
    return submitButton;
  },

  /**
   * Handles the hide form function.
   * @function
   * @param {Event} e - The event triggering the hideForm function.
   */
  hideForm(e) {
    document.querySelector("#step__1").classList.toggle("hidden");
    document.querySelector("#step__2").classList.toggle("hidden");
    document.querySelector("#cuej__block-creation-form").innerHTML = "";
  },

  /**
   * Assigns the create block function to the create button.
   * @function
   */
  assignButtons() {
    const blockButtons = document.querySelectorAll(".cuej__block-creation");
    for (const button of blockButtons) {
      button.addEventListener("click", this.displayForm.bind(this));
    }

    const backbutton = document.querySelector("#cuej__block-creation-back");
    backbutton.addEventListener("click", this.hideForm.bind(this));
  }
};

export default block_create;