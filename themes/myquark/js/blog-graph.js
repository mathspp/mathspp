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
  const nodeFilters = Array.from(root.querySelectorAll('[data-filter="node"]'));
  const edgeFilters = Array.from(root.querySelectorAll('[data-filter="edge"]'));

  const graphUrl = root.dataset.graphUrl;
  const siteUrl = (root.dataset.siteUrl || "").replace(/\/$/, "");
  let cy;
  let graph;

  function setStatus(message) {
    status.textContent = message || "";
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
    const visibleEdgeTypes = selectedValues(edgeFilters);
    const query = search.value.trim().toLowerCase();

    cy.batch(function () {
      cy.nodes().forEach(function (node) {
        const visible = visibleNodeTypes.has(node.data("type")) && nodeMatchesSearch(node, query);
        node.style("display", visible ? "element" : "none");
      });

      cy.edges().forEach(function (edge) {
        const visible =
          visibleEdgeTypes.has(edge.data("type")) &&
          edge.source().style("display") !== "none" &&
          edge.target().style("display") !== "none";
        edge.style("display", visible ? "element" : "none");
      });
    });

    const visibleArticles = cy.nodes('[type = "article"]').filter((node) => node.visible()).length;
    const visibleTags = cy.nodes('[type = "tag"]').filter((node) => node.visible()).length;
    setStatus(`${visibleArticles} articles and ${visibleTags} tags visible.`);
  }

  function runLayout() {
    cy.layout({
      name: "cose",
      animate: true,
      animationDuration: 450,
      fit: true,
      padding: 36,
      nodeRepulsion: 6500,
      idealEdgeLength: 120,
      edgeElasticity: 80,
      gravity: 0.22,
      numIter: 900,
    }).run();
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
      maxZoom: 3,
      wheelSensitivity: 0.18,
      style: [
        {
          selector: "node",
          style: {
            label: "data(label)",
            "font-size": 10,
            "text-max-width": 120,
            "text-wrap": "wrap",
            "text-valign": "bottom",
            "text-halign": "center",
            "text-margin-y": 5,
            color: "#20272b",
            "background-color": "#8fb7a8",
            "border-color": "#ffffff",
            "border-width": 2,
            width: 28,
            height: 28,
          },
        },
        {
          selector: 'node[type = "article"]',
          style: {
            shape: "round-rectangle",
            width: 42,
            height: 24,
            "background-color": "#2e5f73",
          },
        },
        {
          selector: 'node[type = "tag"]',
          style: {
            shape: "ellipse",
            width: 24,
            height: 24,
            "background-color": "#d59a3d",
          },
        },
        {
          selector: "edge",
          style: {
            width: 1.25,
            "curve-style": "bezier",
            "target-arrow-shape": "triangle",
            "target-arrow-color": "#6f756f",
            "line-color": "#6f756f",
            opacity: 0.55,
          },
        },
        {
          selector: 'edge[type = "tag"]',
          style: {
            "target-arrow-shape": "none",
            "line-color": "#d59a3d",
            opacity: 0.28,
          },
        },
        {
          selector: 'edge[type = "internal-link"]',
          style: {
            "line-color": "#2e5f73",
            "target-arrow-color": "#2e5f73",
            opacity: 0.62,
          },
        },
        {
          selector: "node:selected",
          style: {
            "border-color": "#b64b3a",
            "border-width": 4,
          },
        },
        {
          selector: "edge:selected",
          style: {
            width: 3,
            opacity: 1,
          },
        },
      ],
    });

    cy.on("tap", "node", function (event) {
      updateSelection(event.target.data());
    });
    cy.on("dbltap", "node", function (event) {
      const url = absoluteUrl(event.target.data("url"));
      if (url) {
        window.location.href = url;
      }
    });
    cy.on("tap", function (event) {
      if (event.target === cy) {
        updateSelection(null);
      }
    });

    runLayout();
    applyFilters();
    updateSelection(null);
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

  [search].concat(nodeFilters, edgeFilters).forEach(function (input) {
    input.addEventListener("input", applyFilters);
    input.addEventListener("change", applyFilters);
  });

  resetButton.addEventListener("click", function () {
    if (cy) {
      applyFilters();
      runLayout();
    }
  });
})();
