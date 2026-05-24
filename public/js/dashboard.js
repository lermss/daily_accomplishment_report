(() => {
    const config = window.dashboardConfig || {};
    const userFormOptions = config.userFormOptions || {};
    const initialRole = config.oldValues?.role || config.initialRole || "hr-super-admin";

    const modal = document.querySelector("[data-user-modal]");
    const openButtons = Array.from(document.querySelectorAll("[data-open-user-modal]"));
    const closeButton = document.querySelector("[data-close-user-modal]");
    const form = document.querySelector("[data-user-form]");
    const confirmModal = document.querySelector("[data-confirm-modal]");
    const confirmTitle = document.querySelector("[data-confirm-title]");
    const confirmMessage = document.querySelector("[data-confirm-message]");
    const confirmCancel = document.querySelector("[data-confirm-cancel]");
    const confirmSubmit = document.querySelector("[data-confirm-submit]");

    let pendingForm = null;

    const setConfirmVisible = (visible) => {
        if (confirmModal) {
            confirmModal.classList.toggle("is-visible", visible);
        }
    };

    document.querySelectorAll("[data-confirm-trigger]").forEach((button) => {
        button.addEventListener("click", () => {
            pendingForm = document.getElementById(button.dataset.formId);

            if (!pendingForm) {
                return;
            }

            confirmTitle.textContent = button.dataset.confirmTitle || "Confirm Action";
            confirmMessage.textContent = button.dataset.confirmMessage || "";
            setConfirmVisible(true);
        });
    });

    confirmCancel?.addEventListener("click", () => {
        setConfirmVisible(false);
        pendingForm = null;
    });
    confirmSubmit?.addEventListener("click", () => pendingForm?.submit());

    confirmModal?.addEventListener("click", (event) => {
        if (event.target === confirmModal) {
            setConfirmVisible(false);
            pendingForm = null;
        }
    });

    if (!modal || !form) {
        return;
    }

    const methodField = form.querySelector("[data-user-form-method]");
    const titleNode = document.querySelector("[data-user-modal-title]");
    const radios = Array.from(form.querySelectorAll("[data-role-radio]"));
    const fieldRows = Object.fromEntries(
        Array.from(form.querySelectorAll("[data-field]")).map((node) => [node.dataset.field, node])
    );
    const combinedNameInput = form.querySelector('[data-combined-name]');
    const firstNameInput = form.querySelector('input[name="first_name"]');
    const middleNameInput = form.querySelector('input[name="middle_name"]');
    const lastNameInput = form.querySelector('input[name="last_name"]');

    const splitFullName = (fullName = "") => {
        const normalized = String(fullName).trim().replace(/\s+/g, " ");

        if (!normalized) {
            return { first_name: "", middle_name: "", last_name: "" };
        }

        const parts = normalized.split(" ");

        if (parts.length === 1) {
            return { first_name: parts[0], middle_name: "", last_name: "" };
        }

        if (parts.length === 2) {
            return { first_name: parts[0], middle_name: "", last_name: parts[1] };
        }

        return {
            first_name: parts[0],
            middle_name: parts.slice(1, -1).join(" "),
            last_name: parts[parts.length - 1]
        };
    };

    const buildFullName = () => {
        return [firstNameInput?.value, middleNameInput?.value, lastNameInput?.value]
            .map((value) => (value || "").trim())
            .filter(Boolean)
            .join(" ");
    };

    const syncCombinedName = () => {
        if (!combinedNameInput) {
            return;
        }

        combinedNameInput.value = buildFullName();
    };

    const setNameParts = (values = {}) => {
        const parsedFromName = splitFullName(values.name || "");
        const firstName = values.first_name ?? parsedFromName.first_name;
        const middleName = values.middle_name ?? parsedFromName.middle_name;
        const lastName = values.last_name ?? parsedFromName.last_name;

        if (firstNameInput) {
            firstNameInput.value = firstName || "";
        }

        if (middleNameInput) {
            middleNameInput.value = middleName || "";
        }

        if (lastNameInput) {
            lastNameInput.value = lastName || "";
        }

        syncCombinedName();
    };

    const fillSelect = (select, placeholder, values, selectedValue) => {
        if (!select) {
            return;
        }

        select.innerHTML = "";

        const firstOption = document.createElement("option");
        firstOption.value = "";
        firstOption.textContent = placeholder;
        select.appendChild(firstOption);

        values.forEach((value) => {
            const option = document.createElement("option");
            option.value = value;
            option.textContent = value;
            option.selected = selectedValue === value;
            select.appendChild(option);
        });
    };

    const applyRole = (roleValue, values = {}) => {
        const roleConfig = userFormOptions[roleValue];

        if (!roleConfig) {
            return;
        }

        Object.entries(fieldRows).forEach(([fieldName, fieldRow]) => {
            const input = fieldRow.querySelector("input, select");
            const shouldShow = roleConfig.fields.includes(fieldName) || fieldName === "name" || fieldName === "email";

            fieldRow.hidden = !shouldShow;

            if (!input) {
                return;
            }

            const shouldRequire = roleConfig.fields.includes(fieldName) || fieldName === "name" || fieldName === "email";
            input.required = shouldRequire;

            if (fieldName === "name") {
                if (combinedNameInput) {
                    combinedNameInput.required = shouldRequire;
                }

                if (firstNameInput) {
                    firstNameInput.required = shouldRequire;
                }

                if (lastNameInput) {
                    lastNameInput.required = shouldRequire;
                }
            }

            if (!shouldShow) {
                fieldRow.querySelectorAll("input, select").forEach((fieldInput) => {
                    fieldInput.value = "";
                });
            } else if (values[fieldName] !== undefined && input.tagName === "INPUT") {
                input.value = values[fieldName] || "";
            }
        });

        if (fieldRows.name && !fieldRows.name.hidden) {
            setNameParts(values);
        }

        fillSelect(form.querySelector('select[name="project"]'), "Project", roleConfig.projectOptions || [], values.project || "");
        fillSelect(form.querySelector('select[name="bureau"]'), "Bureau", roleConfig.bureauOptions || [], values.bureau || "");
        fillSelect(form.querySelector('select[name="division"]'), roleConfig.divisionLabel || "Division", roleConfig.divisionOptions || [], values.division || "");
        fillSelect(form.querySelector('select[name="office"]'), "Office", roleConfig.officeOptions || [], values.office || "");
    };

    const setVisible = (visible) => modal.classList.toggle("is-visible", visible);

    const setCreateMode = () => {
        form.action = form.dataset.storeAction;
        methodField.value = "";
        titleNode.textContent = "Create New User";
        form.reset();

        const defaultRadio = radios.find((radio) => radio.value === initialRole) || radios[0];
        defaultRadio.checked = true;

        applyRole(defaultRadio.value, config.oldValues || {});

        setNameParts(config.oldValues || {});
        form.querySelector('input[name="email"]').value = config.oldValues?.email || "";
        const posInput = form.querySelector('input[name="position"]');
        if (posInput) posInput.value = config.oldValues?.position || "";
        form.querySelector('input[name="institution"]').value = config.oldValues?.institution || "";
    };

    const setEditMode = (userData) => {
        form.action = form.dataset.updateTemplate.replace("__USER__", userData.id);
        methodField.value = "PUT";
        titleNode.textContent = "Edit User";

        setNameParts(userData);
        form.querySelector('input[name="email"]').value = userData.email || "";
        const posInputEdit = form.querySelector('input[name="position"]');
        if (posInputEdit) posInputEdit.value = userData.position || "";
        form.querySelector('input[name="institution"]').value = userData.institution || "";

        const targetRadio = radios.find((radio) => radio.value === userData.role) || radios[0];
        targetRadio.checked = true;
        applyRole(targetRadio.value, userData);
    };

    openButtons.forEach((button) => {
        button.addEventListener("click", () => {
            if (button.dataset.mode === "edit" && button.dataset.user) {
                setEditMode(JSON.parse(button.dataset.user));
            } else {
                setCreateMode();
            }

            setVisible(true);
        });
    });

    closeButton?.addEventListener("click", () => setVisible(false));
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            setVisible(false);
        }
    });

    radios.forEach((radio) => {
        radio.addEventListener("change", () => applyRole(radio.value, {}));
    });

    [firstNameInput, middleNameInput, lastNameInput].forEach((input) => {
        input?.addEventListener("input", syncCombinedName);
    });

    form.addEventListener("submit", syncCombinedName);

    applyRole(initialRole, config.oldValues || {});
})();
