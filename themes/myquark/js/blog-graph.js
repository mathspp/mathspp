(function () {
  "use strict";

  const root = document.querySelector(".blog-graph");
  if (!root) {
    return;
  }

  const canvas = document.getElementById("blog-graph-canvas");
  const status = root.querySelector(".blog-graph-status");
  const search = root.querySelector("#blog-graph-search");
  const resetButton = root.querySelector(".blog-graph-reset");
  const selection = root.querySelector(".blog-graph-selection");
  const panelToggle = root.querySelector(".blog-graph-panel-toggle");
  const nodeFilters = Array.from(root.querySelectorAll('[data-filter="node"]'));

  const graphUrl = root.dataset.graphUrl;
  const siteUrl = (root.dataset.siteUrl || "").replace(/\/$/, "");
  let cy;
  let graph;

  function setStatus(message) {
    status.textContent = message || "";
  }

  function themeColor(name) {
    const probe = document.createElement("span");
    probe.style.color = `var(${name})`;
    root.appendChild(probe);
    const color = getComputedStyle(probe).color;
    probe.remove();
    return color;
  }

  function selectedValues(inputs) {
    return new Set(inputs.filter((input) => input.checked).map((input) => input.value));
  }

  function absoluteUrl(path) {
    if (!path) {
      return "";
    }
    if (/^https?:\/\//i.test(path)) {
      return path;
    }
    return siteUrl + path;
  }

  function nodeMatchesSearch(node, query) {
    if (!query) {
      return true;
    }
    const data = node.data();
    const haystack = [
      data.label,
      data.title,
      data.slug,
      data.tag,
      Array.isArray(data.tags) ? data.tags.join(" ") : "",
    ]
      .join(" ")
      .toLowerCase();
    return haystack.includes(query);
  }

  function applyFilters() {
    if (!cy) {
      return;
    }

    const visibleNodeTypes = selectedValues(nodeFilters);
    const query = search.value.trim().toLowerCase();

    cy.batch(function () {
      cy.nodes().forEach(function (node) {
        const visible = visibleNodeTypes.has(node.data("type")) && nodeMatchesSearch(node, query);
        node.style("display", visible ? "element" : "none");
      });

      cy.edges().forEach(function (edge) {
        const visible =
          edge.source().style("display") !== "none" &&
          edge.target().style("display") !== "none";
        edge.style("display", visible ? "element" : "none");
      });
    });

    const visibleArticles = cy.nodes('[type = "article"]').filter((node) => node.visible()).length;
    const visibleTags = cy.nodes('[type = "tag"]').filter((node) => node.visible()).length;
    const visibleElements = cy.elements().filter((element) => element.visible());
    if (visibleElements.length) {
      cy.fit(visibleElements, 64);
    }
    setStatus(`${visibleArticles} articles and ${visibleTags} tags visible.`);
  }

  function clearFocus() {
    if (!cy) {
      return;
    }
    cy.elements().removeClass("dimmed focused-neighborhood");
  }

  function focusNeighborhood(node) {
    const visibleEdges = node.connectedEdges().filter((edge) => edge.visible());
    const neighborhood = node
      .union(visibleEdges)
      .union(visibleEdges.connectedNodes())
      .filter((element) => element.visible());

    cy.batch(function () {
      clearFocus();
      cy.elements().filter((element) => element.visible()).addClass("dimmed");
      neighborhood.removeClass("dimmed").addClass("focused-neighborhood");
    });
  }

  function setPanelCollapsed(collapsed) {
    root.classList.toggle("is-panel-collapsed", collapsed);
    panelToggle.setAttribute("aria-expanded", String(!collapsed));
    panelToggle.setAttribute("title", collapsed ? "Expand controls" : "Collapse controls");
    panelToggle.querySelector(".blog-graph-panel-toggle-text").textContent = collapsed
      ? "Graph controls"
      : "Controls";
  }

  function layoutBox() {
    const width = Math.max(canvas.clientWidth * 3.2, 3000);
    const height = Math.max(canvas.clientHeight * 3.2, 2200);
    return { x1: 0, y1: 0, w: width, h: height };
  }

  function runLayout() {
    const visibleElements = cy.elements().filter((element) => element.visible());
    visibleElements.layout({
      name: "cose",
      animate: "end",
      animationDuration: 500,
      fit: true,
      padding: 64,
      randomize: true,
      componentSpacing: 160,
      nodeOverlap: 48,
      nodeRepulsion: 520000,
      idealEdgeLength: 190,
      edgeElasticity: 45,
      nestingFactor: 0.12,
      gravity: 0.04,
      numIter: 1800,
      initialTemp: 900,
      coolingFactor: 0.94,
      minTemp: 1,
      boundingBox: layoutBox(),
    }).run();
  }

  function buildColors() {
    return {
      bg: themeColor("--bg"),
      tx: themeColor("--tx"),
      tx2: themeColor("--tx-2"),
      accent2: themeColor("--accent-2"),
      blue: themeColor("--bl"),
      cyan: themeColor("--cy"),
      yellow: themeColor("--ye"),
      red: themeColor("--re"),
    };
  }

  function graphStyles(colors) {
    return [
      {
        selector: "node",
        style: {
          label: "data(label)",
          "font-size": 10,
          "text-max-width": 120,
          "text-wrap": "wrap",
          "text-valign": "bottom",
          "text-halign": "center",
          "text-margin-y": 7,
          "min-zoomed-font-size": 8,
          color: colors.tx,
          "text-background-color": colors.bg,
          "text-background-opacity": 0.72,
          "text-background-padding": 2,
          "background-color": colors.cyan,
          "border-color": colors.bg,
          "border-width": 2,
          width: "data(width)",
          height: "data(height)",
        },
      },
      {
        selector: 'node[type = "article"]',
        style: {
          shape: "round-rectangle",
          "background-color": colors.blue,
        },
      },
      {
        selector: 'node[type = "tag"]',
        style: {
          shape: "ellipse",
          "background-color": colors.yellow,
        },
      },
      {
        selector: "edge",
        style: {
          width: 1,
          "curve-style": "bezier",
          "target-arrow-shape": "triangle",
          "target-arrow-color": colors.tx2,
          "line-color": colors.tx2,
          opacity: 0.42,
        },
      },
      {
        selector: 'edge[type = "tag"]',
        style: {
          "target-arrow-shape": "none",
          "line-color": colors.yellow,
          width: 0.75,
          opacity: 0.16,
        },
      },
      {
        selector: 'edge[type = "internal-link"]',
        style: {
          "line-color": colors.accent2,
          "target-arrow-color": colors.accent2,
          opacity: 0.5,
        },
      },
      {
        selector: "node:selected",
        style: {
          "border-color": colors.red,
          "border-width": 4,
        },
      },
      {
        selector: ".dimmed",
        style: {
          opacity: 0.25,
          "text-opacity": 0.25,
        },
      },
      {
        selector: "edge.dimmed",
        style: {
          opacity: 0.12,
        },
      },
      {
        selector: ".focused-neighborhood",
        style: {
          opacity: 1,
          "text-opacity": 1,
        },
      },
    ];
  }

  function updateSelection(data) {
    if (!data) {
      selection.innerHTML = "<strong>Blog graph</strong><span>Select a node to open or inspect it.</span>";
      return;
    }

    const url = absoluteUrl(data.url);
    const meta =
      data.type === "article"
        ? `<span>${escapeHtml(data.date || "Undated")}${data.tags && data.tags.length ? " · " + escapeHtml(data.tags.join(", ")) : ""}</span>`
        : '<span>Tag page</span>';

    selection.innerHTML = [
      `<strong>${escapeHtml(data.label || data.title || data.tag)}</strong>`,
      meta,
      url ? `<a href="${escapeHtml(url)}">Open ${escapeHtml(data.type)}</a>` : "",
    ].join("");
  }

  function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text || "";
    return div.innerHTML;
  }

  function initCytoscape(data) {
    graph = data;

    cy = cytoscape({
      container: canvas,
      elements: {
        nodes: graph.nodes,
        edges: graph.edges,
      },
      minZoom: 0.08,
      maxZoom: 4,
      wheelSensitivity: 0.14,
      hideEdgesOnViewport: true,
      textureOnViewport: true,
      style: graphStyles(buildColors()),
    });
    cy.edges().unselectify();

    cy.on("tap", "node", function (event) {
      const node = event.target;
      focusNeighborhood(node);
      updateSelection(node.data());
    });
    cy.on("dbltap", "node", function (event) {
      const url = absoluteUrl(event.target.data("url"));
      if (url) {
        window.location.href = url;
      }
    });
    cy.on("tap", function (event) {
      if (event.target === cy) {
        clearFocus();
        updateSelection(null);
      }
    });

    applyFilters();
    runLayout();
    updateSelection(null);

    new MutationObserver(function () {
      cy.style().fromJson(graphStyles(buildColors())).update();
    }).observe(document.documentElement, {
      attributes: true,
      attributeFilter: ["data-theme"],
    });
  }

  fetch(graphUrl)
    .then(function (response) {
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }
      return response.json();
    })
    .then(initCytoscape)
    .catch(function (error) {
      setStatus(`Could not load graph data: ${error.message}`);
    });

  search.addEventListener("input", function () {
    clearFocus();
    applyFilters();
    updateSelection(null);
  });

  nodeFilters.forEach(function (input) {
    input.addEventListener("change", function () {
      clearFocus();
      applyFilters();
      runLayout();
    });
  });

  panelToggle.addEventListener("click", function () {
    setPanelCollapsed(!root.classList.contains("is-panel-collapsed"));
    if (cy) {
      window.setTimeout(function () {
        cy.resize();
        cy.fit(cy.elements().filter((element) => element.visible()), 64);
      }, 190);
    }
  });

  resetButton.addEventListener("click", function () {
    if (cy) {
      clearFocus();
      applyFilters();
      runLayout();
    }
  });
})();
