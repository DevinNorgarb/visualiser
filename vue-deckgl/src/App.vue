<template>
  <div id="app">
<div id="control-panel">
  <div>
    <label>Radius</label>
    <input id="radius" type="range" min="1000" max="20000" step="1000" value="1000"></input>
    <span id="radius-value"></span>
  </div>
  <div>
    <label>Coverage</label>
    <input id="coverage" type="range" min="0" max="1" step="0.1" value="1"></input>
    <span id="coverage-value"></span>
  </div>
  <div>
    <label>Upper Percentile</label>
    <input id="upperPercentile" type="range" min="90" max="100" step="1" value="100"></input>
    <span id="upperPercentile-value"></span>
  </div>
</div>

  </div>
</template>

<script>
import "mapbox-gl/dist/mapbox-gl.css";

import { MapboxLayer } from "@deck.gl/mapbox";
import { Deck } from "@deck.gl/core";
import { ScatterplotLayer, GeoJsonLayer, ArcLayer } from "@deck.gl/layers";
import { HexagonLayer } from "@deck.gl/aggregation-layers";

import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";
import { AmbientLight, PointLight, LightingEffect } from "@deck.gl/core";

export default {
  data() {
    return {
      map: {},
      deck: {},
      MAP_STYLE:
        "https://basemaps.cartocdn.com/gl/dark-matter-nolabels-gl-style/style.json",
      DATA_URL:
        "https://raw.githubusercontent.com/visgl/deck.gl-data/master/examples/3d-heatmap/heatmap-data.csv",
      colorRange: [
        [1, 152, 189],
        [73, 227, 206],
        [216, 254, 181],
        [254, 237, 177],
        [254, 173, 84],
        [209, 55, 78],
      ],
      INITIAL_VIEW_STATE: {
        longitude: 18,
        latitude: -33,
        zoom: 6.6,
        minZoom: 5,
        maxZoom: 15,
        pitch: 40.5,
        bearing: -27,
      },
      mapStyle: this.MAP_STYLE,
      radius: 1000,
      upperPercentile: 100,
      coverage: 1,
      material: {
        ambient: 0.64,
        diffuse: 0.6,
        shininess: 32,
        specularColor: [51, 51, 51],
      },
    };
  },
  computed: {
    ambientLight() {
      return new AmbientLight({
        color: [255, 255, 255],
        intensity: 1.0,
      });
    },
    pointLight1() {
      return new PointLight({
        color: [255, 255, 255],
        intensity: 0.8,
        position: [18, -33.807751, 80000],
      });
    },
    pointLight2() {
      return new PointLight({
        color: [255, 255, 255],
        intensity: 0.8,
        position: [19, -33.807751, 8000],
      });
    },

    lightingEffect() {
      return new LightingEffect({
        ...this.ambientLight,
        ...this.pointLight1,
        ...this.pointLight2,
      });
    },
    mapData() {
      return require("d3-request").csv(this.DATA_URL, (error, response) => {
        if (!error) {
          const data = response.map((d) => [Number(d.lng), Number(d.lat)]);
          return data;
        }
      });
    },
  },
  mounted() {
    return this.laodFake();





mapboxgl.accessToken =
      "pk.eyJ1IjoidWJlcmRhdGEiLCJhIjoiY2pudzRtaWloMDAzcTN2bzN1aXdxZHB5bSJ9.2bkj3IiRC8wj3jLThvDGdA";






    this.map = new mapboxgl.Map({
      container: "app",
      style: "mapbox://styles/mapbox/light-v10",
      // style: 'https://tiles.devsdev.com/style.json',
      center: [18, -33],
      zoom: 9,
    });

    mapboxgl.accessToken =
      "pk.eyJ1IjoidWJlcmRhdGEiLCJhIjoiY2pudzRtaWloMDAzcTN2bzN1aXdxZHB5bSJ9.2bkj3IiRC8wj3jLThvDGdA";

    // wait for map to be ready
    this.map.on("load", () => {
      this.loadLayer();
      // var deck = this.deck;

      // update the layer
      // this.deck.setProps({
      //   layers: [],
      // });
    });
  },
  methods: {
    laodFake() {
      mapboxgl.accessToken =
        "pk.eyJ1IjoicnNiYXVtYW5uIiwiYSI6ImNqNmhkZnhkZDA4M3Yyd3AwZDR4cmdhcDIifQ.TGKKAC6pPP0L-uMDJ5xFAA";

      // var { MapboxLayer, HexagonLayer } = deck;

      //Create the Mapbox map
      var map = new mapboxgl.Map({
        container: document.body,
        style: "mapbox://styles/mapbox/dark-v10?optimize=true",
        center: [-1.4157, 52.2324],
        zoom: 6,
        pitch: 40.5,
        antialias: true,
      });

      // Get Data for visual
      var DATA_URL =
        "https://raw.githubusercontent.com/uber-common/deck.gl-data/master/examples/3d-heatmap/heatmap-data.csv";

      //Create the deck.gl hexagon layer and style for the data
      var OPTIONS = ["radius", "coverage", "upperPercentile"];
      var COLOR_RANGE = [
        [1, 152, 189],
        [73, 227, 206],
        [216, 254, 181],
        [254, 237, 177],
        [254, 173, 84],
        [209, 55, 78],
      ];
      var LIGHT_SETTINGS = {
        lightsPosition: [
          -0.144528, 49.739968, 8000, -3.807751, 54.104682, 8000,
        ],
        ambientRatio: 0.4,
        diffuseRatio: 0.6,
        specularRatio: 0.2,
        lightsStrength: [0.8, 0.0, 0.8, 0.0],
        numberOfLights: 2,
      };

      var hexagonLayer;

      //Add the deck.gl Custom Layer to the map once the Mapbox map loads
      map.on("style.load", () => {
        hexagonLayer = new MapboxLayer({
          type: HexagonLayer,
          id: "heatmap",
          data: require("d3").csv(DATA_URL),
          radius: 1000,
          coverage: 1,
          upperPercentile: 100,
          colorRange: COLOR_RANGE,
          elevationRange: [0, 1000],
          elevationScale: 250,
          extruded: true,
          getPosition: (d) => [Number(d.lng), Number(d.lat)],
          lightSettings: LIGHT_SETTINGS,
          opacity: 1,
        });

        // Add the deck.gl hex layer below labels in the Mapbox map
        map.addLayer(hexagonLayer, "waterway-label");
      });

      // Add sliders to change the layer's settings based on user input
      OPTIONS.forEach((key) => {
        document.getElementById(key).onchange = (evt) => {
          // var value = Number(evt.target.value);
          // document.getElementById(key + "-value").innerHTML = value;
          // if (hexagonLayer) {
          //   hexagonLayer.setProps({
          //     [key]: value,
          //   });
          // }
        };
      });

      // const AIR_PORTS =
      //   "https://d2ad6b4ur7yvpq.cloudfront.net/naturalearth-3.3.0/ne_10m_airports.geojson";

      // const deckgl = new Deck({
      //   container: "app",
      //   // Set your Mapbox access token here
      //   // mapboxApiAccessToken: '<your mapbox token>',
      //   mapboxApiAccessToken:
      //     "pk.eyJ1IjoidWJlcmRhdGEiLCJhIjoiY2pudzRtaWloMDAzcTN2bzN1aXdxZHB5bSJ9.2bkj3IiRC8wj3jLThvDGdA",

      //   initialViewState: {
      //     latitude: 51.47,
      //     longitude: 0.45,
      //     zoom: 4,
      //     bearing: 0,
      //     pitch: 30,
      //   },
      //   controller: true,

      //   layers: [
      //     new GeoJsonLayer({
      //       id: "airports",
      //       data: AIR_PORTS,
      //       // Styles
      //       filled: true,
      //       pointRadiusMinPixels: 2,
      //       pointRadiusScale: 2000,
      //       getPointRadius: (f) => 11 - f.properties.scalerank,
      //       getFillColor: [200, 0, 80, 180],
      //       // Interactive props
      //       pickable: true,
      //       autoHighlight: true,
      //       onClick: (info) =>
      //         info.object &&
      //         alert(
      //           `${info.object.properties.name} (${info.object.properties.abbrev})`
      //         ),
      //     }),
      //     new ArcLayer({
      //       id: "arcs",
      //       data: AIR_PORTS,
      //       dataTransform: (d) =>
      //         d.features.filter((f) => f.properties.scalerank < 4),
      //       // Styles
      //       getSourcePosition: (f) => [-0.4531566, 51.4709959], // London
      //       getTargetPosition: (f) => f.geometry.coordinates,
      //       getSourceColor: [0, 128, 200],
      //       getTargetColor: [200, 0, 80],
      //       getWidth: 1,
      //     }),
      //   ],
      // });
    },
    loadLayer() {
      const hexagonLayer = new HexagonLayer({
        id: "heatmap",
        colorRange: this.colorRange,
        coverage: this.coverage,
        data: this.mapData,
        elevationRange: [0, 3000],
        elevationScale: this.mapData && this.mapData.length ? 50 : 0,
        extruded: true,
        getPosition: (d) => d,
        pickable: true,
        radius: this.radius,
        upperPercentile: this.upperPercentile,
        material: this.material,

        transitions: {
          elevationScale: 3000,
        },
      });
      this.deck = new Deck({
        initialViewState: {
          longitude: -1.4157,
          latitude: 52.2324,
          zoom: 6,
          minZoom: 5,
          maxZoom: 15,
          pitch: 40.5,
        },
        controller: true,
        gl: this.map.painter.context.gl,
        layers: [hexagonLayer],
        style: this.MAP_STYLE,
      });
      // this.deck.setProps({
      //   layers: [hexagonLayer],
      // });
      // var deck = this.deck
      this.map.addLayer(new MapboxLayer(hexagonLayer));
    },
  },
};
</script>

<style>
html,
body,
#app {
  margin: 0;
  width: 100%;
  height: 100%;
}
</style>
