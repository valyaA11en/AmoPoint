(() => {
    'use strict';

    const typeSelect = document.querySelector('select[name="type_val"]');

    if (!typeSelect) {
        console.warn('[type-fields-filter] Type select was not found.');
        return;
    }

    const normalize = (value) => String(value ?? '').trim().toLowerCase();

    const isBr = (node) => {
        return node?.nodeType === Node.ELEMENT_NODE && node.tagName === 'BR';
    };

    const isTrackableField = (element) => {
        if (element === typeSelect) {
            return false;
        }

        if (!element.name) {
            return false;
        }

        if (element.tagName === 'IFRAME') {
            return false;
        }

        if (element.type === 'hidden') {
            return false;
        }

        return ['INPUT', 'TEXTAREA', 'SELECT', 'BUTTON'].includes(element.tagName);
    };

    const getTrackableFieldFromNodes = (nodes) => {
        return nodes
            .filter((node) => node.nodeType === Node.ELEMENT_NODE)
            .flatMap((node) => {
                const elements = [node, ...node.querySelectorAll?.('[name]') ?? []];

                return elements.filter(isTrackableField);
            })[0] ?? null;
    };

    const wrapNodes = (nodes) => {
        const wrapper = document.createElement('span');

        wrapper.dataset.typeFieldsFilterWrapper = 'true';

        nodes[0].parentNode.insertBefore(wrapper, nodes[0]);

        nodes.forEach((node) => {
            wrapper.appendChild(node);
        });

        return wrapper;
    };

    const createLineGroups = () => {
        const bodyNodes = Array.from(document.body.childNodes);
        const groups = [];
        let currentGroup = [];

        bodyNodes.forEach((node, index) => {
            currentGroup.push(node);

            const nextNode = bodyNodes[index + 1];
            const isEndOfLine = isBr(node) && !isBr(nextNode);

            if (isEndOfLine || index === bodyNodes.length - 1) {
                groups.push(currentGroup);
                currentGroup = [];
            }
        });

        return groups;
    };

    const fields = createLineGroups()
        .map((nodes) => {
            const field = getTrackableFieldFromNodes(nodes);

            if (!field) {
                return null;
            }

            return {
                name: normalize(field.name),
                wrapper: wrapNodes(nodes),
            };
        })
        .filter(Boolean);

    const applyFilter = () => {
        const selectedValue = normalize(typeSelect.value);

        fields.forEach(({ name, wrapper }) => {
            wrapper.hidden = !name.includes(selectedValue);
        });
    };

    typeSelect.addEventListener('change', applyFilter);

    applyFilter();

    console.log('[type-fields-filter] initialized', {
        selectedType: typeSelect.value,
        fields: fields.map((field) => field.name),
    });
})();