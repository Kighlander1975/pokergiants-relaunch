document.addEventListener("DOMContentLoaded", function () {
    const perPageSelect = document.getElementById("users-per-page");
    const filterInputs = document.querySelectorAll("[data-filter-type]");
    const resetFiltersButton = document.querySelector("[data-reset-filters]");

    if (!filterInputs.length) {
        return;
    }

    const buildUrl = (roles, statuses, perPage) => {
        const params = new URLSearchParams();

        if (perPage) {
            params.set("per_page", perPage);
        }

        roles.forEach((role) => params.append("roles[]", role));
        statuses.forEach((status) => params.append("statuses[]", status));

        const queryString = params.toString();
        return (
            window.location.pathname + (queryString ? `?${queryString}` : "")
        );
    };

    const gatherFilters = () => {
        const roles = new Set();
        const statuses = new Set();

        filterInputs.forEach((input) => {
            if (!input.checked) {
                return;
            }

            if (input.dataset.filterType === "role") {
                roles.add(input.value);
            }

            if (input.dataset.filterType === "status") {
                statuses.add(input.value);
            }
        });

        return { roles, statuses };
    };

    const shouldShowInactiveReset = (roles, statuses) =>
        roles.size > 0 || statuses.size > 0;

    const updateResetButtonState = (roles, statuses) => {
        if (!resetFiltersButton) {
            return;
        }

        const activeClasses = [
            "bg-indigo-600",
            "text-white",
            "border-indigo-600",
            "hover:bg-indigo-700",
            "focus:ring-indigo-500",
        ];
        const inactiveClasses = [
            "bg-white",
            "text-gray-700",
            "border-gray-300",
            "hover:bg-gray-50",
            "focus:ring-gray-500",
        ];
        const isActive = !shouldShowInactiveReset(roles, statuses);

        activeClasses.forEach((className) =>
            resetFiltersButton.classList.toggle(className, isActive)
        );
        inactiveClasses.forEach((className) =>
            resetFiltersButton.classList.toggle(className, !isActive)
        );
    };

    const buildFilterUrlAndNavigate = (roles, statuses) => {
        const perPage = perPageSelect?.value || "";
        window.location.href = buildUrl(roles, statuses, perPage);
    };

    const updateRadioGroupState = (group) => {
        const groupRadios = document.querySelectorAll(
            `input[type="radio"][data-status-group="${group}"]`
        );

        groupRadios.forEach((radio) => {
            radio.dataset.active = radio.checked ? "true" : "false";
        });
    };

    const setupStatusRadios = () => {
        const statusRadios = document.querySelectorAll(
            'input[type="radio"][data-status-group]'
        );

        statusRadios.forEach((radio) => {
            radio.addEventListener("click", (event) => {
                if (radio.dataset.active === "true") {
                    event.preventDefault();
                    radio.checked = false;
                    updateRadioGroupState(radio.dataset.statusGroup);
                    radio.dispatchEvent(new Event("change", { bubbles: true }));
                }
            });

            radio.addEventListener("change", () => {
                updateRadioGroupState(radio.dataset.statusGroup);
            });
        });

        const groups = new Set(
            Array.from(statusRadios).map((radio) => radio.dataset.statusGroup)
        );
        groups.forEach((group) => updateRadioGroupState(group));
    };

    setupStatusRadios();

    filterInputs.forEach((input) => {
        input.addEventListener("change", () => {
            const filters = gatherFilters();
            updateResetButtonState(filters.roles, filters.statuses);
            buildFilterUrlAndNavigate(filters.roles, filters.statuses);
        });
    });

    resetFiltersButton?.addEventListener("click", () => {
        filterInputs.forEach((input) => {
            input.checked = false;
        });

        const filters = gatherFilters();
        updateResetButtonState(filters.roles, filters.statuses);
        buildFilterUrlAndNavigate(filters.roles, filters.statuses);
    });

    perPageSelect?.addEventListener("change", () => {
        const filters = gatherFilters();
        buildFilterUrlAndNavigate(filters.roles, filters.statuses);
    });
});
