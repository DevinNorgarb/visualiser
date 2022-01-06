<template>
  <div id="app">
<div id="control-panel">
  <div>
    <label>Radius</label>
    <input id="radius" type="range" min="1000" max="20000" step="1000"@change="loadFake" v-model="radius"></input>
    <span id="radius-value">{{radius}}</span>
  </div>
  <div>
    <label>Coverage</label>
    <input id="coverage" type="range" min="0" max="1" step="0.1" @change="loadFake" v-model="coverage"></input>
    <span id="coverage-value">{{coverage}}</span>
  </div>
  <div>
    <label>Upper Percentile</label>
    <input id="upperPercentile" type="range" min="90" max="100" step="1"  @change="loadFake" v-model="upperPercentile"></input>
    <span id="upperPercentile-value">{{upperPercentile}}</span>
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
// import data from '@/data/someData.json'

export default {
  data() {
    return {
      map: {},
      deck: {},
      MAP_STYLE:
        "https://basemaps.cartocdn.com/gl/dark-matter-nolabels-gl-style/style.json",
      DATA_URL:
        "/pup_with_order_processes_count.csv",
      colorRange: [
        [1, 152, 189],
        [73, 227, 206],
        [216, 254, 181],
        [254, 237, 177],
        [254, 173, 84],
        [209, 55, 78],
      ],
      LIGHT_SETTINGS: {
        lightsPosition: [
          30, -18, 8000, 34, -19, 8000,
        ],
        ambientRatio: 0.4,
        diffuseRatio: 0.6,
        specularRatio: 0.2,
        lightsStrength: [1, 1, 1, 1],
        numberOfLights: 5,
      },
      INITIAL_VIEW_STATE: {
        longitude: 18,
        latitude: -33,
        zoom: 6.6,
        minZoom: 5,
        maxZoom: 15,
        pitch: 40.5,
        bearing: 10,
      },
      mapStyle: this.MAP_STYLE,
      radius: 1000,
      upperPercentile: 90,
      coverage: 1,
      material: {
        ambient: 1,
        diffuse: 1,
        shininess: 100,
        specularColor: [100, 100, 100],
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
    return this.loadFake();

mapboxgl.accessToken =
      "pk.eyJ1IjoidWJlcmRhdGEiLCJhIjoiY2pudzRtaWloMDAzcTN2bzN1aXdxZHB5bSJ9.2bkj3IiRC8wj3jLThvDGdA";
          this.map = new mapboxgl.Map({
      container: "app",
      style: "mapbox://styles/mapbox/light-v10",
      center: [18, -33],
      zoom: 9,
    });

this.map.on("load", () => {
      this.loadLayer();
  })
  },

  methods: {
    calcElevationValue(d) {
                  if (d.length > 1) {
                    var orders = 0
                    for (let index = 0; index < d.length; index++) {
                      const element = d[index];
                      orders += Number(element.orders);
                    }
                    return orders
                  }
              return Number(d[0].orders);

    },
    loadFake() {
      mapboxgl.accessToken =
      "pk.eyJ1IjoidWJlcmRhdGEiLCJhIjoiY2pudzRtaWloMDAzcTN2bzN1aXdxZHB5bSJ9.2bkj3IiRC8wj3jLThvDGdA";

      var map = new mapboxgl.Map({
        container: "app",
        style: "mapbox://styles/mapbox/dark-v10?optimize=true",
        center: [ 18,-33],
        zoom: 7,
        pitch: 60,
        antialias: true,
        ...this.INITIAL_VIEW_STATE
      });

      // Get Data for visual

      //Create the deck.gl hexagon layer and style for the data
      var OPTIONS = ["radius", "coverage", "upperPercentile"];

      var hexagonLayer;
      map.on("style.load", () => {
        hexagonLayer = new MapboxLayer({
          type: HexagonLayer,
          id: "heatmap",
          data: require("d3").csv(this.DATA_URL),
          radius: this.radius,
          coverage: this.coverage,
          upperPercentile: this.upperPercentile,
          colorRange: this.colorRange,
          elevationRange: [0, 1000],
          elevationScale: 250,
          extruded: true,
          getElevationValue: (d) => this.calcElevationValue(d),
          getPosition: (d) => [ Number(d.lng), Number(d.lat)],
          lightSettings: this.LIGHT_SETTINGS,
          opacity: 1,
        });

        this.hexagonLayer = hexagonLayer;

        // Add the deck.gl hex layer below labels in the Mapbox map
        map.addLayer(hexagonLayer, "waterway-label");
      });

      // Add sliders to change the layer's settings based on user input
this.updateValues(hexagonLayer)
    },
    updateValues(hexagonLayer, optiomns) {
            var OPTIONS = ["radius", "coverage", "upperPercentile"];
    },
    loadLayer() {
      const hexagonLayer = new HexagonLayer({
        id: "heatmap",
        colorRange: this.colorRange,
        coverage: this.coverage,
        data: this.mapData,
        elevationRange: [0, 2000],
        elevationScale: this.mapData && this.mapData.length ? 50 : 0,
        extruded: true,
        getPosition: (d) => d,
        pickable: true,
        radius: this.radius,
        upperPercentile: this.upperPercentile,
        material: this.material,
        transitions: {
          elevationScale: 2000,
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
/* html,
body,
.mapboxgl-canvas {
  height: 100%;
  top: 100px;
} */
#app {
  margin: 0;
  width: 100%;
  height: 100%;
}
body {
  width: 100vw;
  height: 100vh;
  margin: 0;
}

#control-panel {
  font-family: Helvetica, Arial, sans-serif;
  position: absolute;
  background: #fff;
  top: 0;
  left: 0;
  margin: 12px;
  padding: 20px;
  z-index: 1;
}

label {
  display: inline-block;
  width: 140px;
}
</style>
