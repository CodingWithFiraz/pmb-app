/**
 * Authentic Still Renderer for ShaderGradient's "HoverGradient" ("Hej" card)
 *
 * Extracted directly from:
 * 1. shadergradient.co bundle (`TextBox_test.Oe5TqQyo.mjs` -> `HoverGradient` / nodeId `JemP8qYm8`):
 *    type: 'sphere', uDensity: 1.1, uStrength: 1, uAmplitude: 5, uFrequency: 5.5,
 *    cDistance: 12.6, cAzimuthAngle: 0, cPolarAngle: 30, cameraZoom: 10.1,
 *    positionX: 0.3, brightness: 1.1, reflection: 0.1, grain: 'off'
 * 2. github.com/ruucm/shadergradient:
 *    - packages/shadergradient/src/shaders/defaults/sphere/vertex.glsl
 *    - packages/shadergradient/src/shaders/defaults/sphere/fragment.glsl
 *
 * Renders a single high-resolution still frame offscreen, applies it as a static
 * CSS background-image on `.hero-gradient-surface`, and immediately destroys the
 * WebGL context so there is zero ongoing GPU usage and zero scroll glitching.
 */

const SPHERE_VERTEX_SHADER = `
attribute vec3 position;
attribute vec3 normal;
attribute vec2 uv;

uniform mat4 uProjectionMatrix;
uniform mat4 uModelViewMatrix;
uniform mat3 uNormalMatrix;

uniform float uTime;
uniform float uSpeed;
uniform float uNoiseDensity;
uniform float uNoiseStrength;
uniform float uFrequency;
uniform float uAmplitude;

varying vec3 vPos;
varying vec3 vNormal;
varying vec3 vViewPosition;

// Classic Perlin noise, periodic variant (exact from sphere/vertex.glsl)
vec3 mod289(vec3 x) {
  return x - floor(x * (1.0 / 289.0)) * 289.0;
}

vec4 mod289(vec4 x) {
  return x - floor(x * (1.0 / 289.0)) * 289.0;
}

vec4 permute(vec4 x) {
  return mod289(((x * 34.0) + 1.0) * x);
}

vec4 taylorInvSqrt(vec4 r) {
  return 1.79284291400159 - 0.85373472095314 * r;
}

vec3 fade(vec3 t) {
  return t * t * t * (t * (t * 6.0 - 15.0) + 10.0);
}

float pnoise(vec3 P, vec3 rep) {
  vec3 Pi0 = mod(floor(P), rep);
  vec3 Pi1 = mod(Pi0 + vec3(1.0), rep);
  Pi0 = mod289(Pi0);
  Pi1 = mod289(Pi1);
  vec3 Pf0 = fract(P);
  vec3 Pf1 = Pf0 - vec3(1.0);
  vec4 ix = vec4(Pi0.x, Pi1.x, Pi0.x, Pi1.x);
  vec4 iy = vec4(Pi0.yy, Pi1.yy);
  vec4 iz0 = Pi0.zzzz;
  vec4 iz1 = Pi1.zzzz;

  vec4 ixy = permute(permute(ix) + iy);
  vec4 ixy0 = permute(ixy + iz0);
  vec4 ixy1 = permute(ixy + iz1);

  vec4 gx0 = ixy0 * (1.0 / 7.0);
  vec4 gy0 = fract(floor(gx0) * (1.0 / 7.0)) - 0.5;
  gx0 = fract(gx0);
  vec4 gz0 = vec4(0.5) - abs(gx0) - abs(gy0);
  vec4 sz0 = step(gz0, vec4(0.0));
  gx0 -= sz0 * (step(0.0, gx0) - 0.5);
  gy0 -= sz0 * (step(0.0, gy0) - 0.5);

  vec4 gx1 = ixy1 * (1.0 / 7.0);
  vec4 gy1 = fract(floor(gx1) * (1.0 / 7.0)) - 0.5;
  gx1 = fract(gx1);
  vec4 gz1 = vec4(0.5) - abs(gx1) - abs(gy1);
  vec4 sz1 = step(gz1, vec4(0.0));
  gx1 -= sz1 * (step(0.0, gx1) - 0.5);
  gy1 -= sz1 * (step(0.0, gy1) - 0.5);

  vec3 g000 = vec3(gx0.x, gy0.x, gz0.x);
  vec3 g100 = vec3(gx0.y, gy0.y, gz0.y);
  vec3 g010 = vec3(gx0.z, gy0.z, gz0.z);
  vec3 g110 = vec3(gx0.w, gy0.w, gz0.w);
  vec3 g001 = vec3(gx1.x, gy1.x, gz1.x);
  vec3 g101 = vec3(gx1.y, gy1.y, gz1.y);
  vec3 g011 = vec3(gx1.z, gy1.z, gz1.z);
  vec3 g111 = vec3(gx1.w, gy1.w, gz1.w);

  vec4 norm0 = taylorInvSqrt(vec4(dot(g000, g000), dot(g010, g010), dot(g100, g100), dot(g110, g110)));
  g000 *= norm0.x;
  g010 *= norm0.y;
  g100 *= norm0.z;
  g110 *= norm0.w;
  vec4 norm1 = taylorInvSqrt(vec4(dot(g001, g001), dot(g011, g011), dot(g101, g101), dot(g111, g111)));
  g001 *= norm1.x;
  g011 *= norm1.y;
  g101 *= norm1.z;
  g111 *= norm1.w;

  float n000 = dot(g000, Pf0);
  float n100 = dot(g100, vec3(Pf1.x, Pf0.yz));
  float n010 = dot(g010, vec3(Pf0.x, Pf1.y, Pf0.z));
  float n110 = dot(g110, vec3(Pf1.xy, Pf0.z));
  float n001 = dot(g001, vec3(Pf0.xy, Pf1.z));
  float n101 = dot(g101, vec3(Pf1.x, Pf0.y, Pf1.z));
  float n011 = dot(g011, vec3(Pf0.x, Pf1.yz));
  float n111 = dot(g111, Pf1);

  vec3 fade_xyz = fade(Pf0);
  vec4 n_z = mix(vec4(n000, n100, n010, n110), vec4(n001, n101, n011, n111), fade_xyz.z);
  vec2 n_yz = mix(n_z.xy, n_z.zw, fade_xyz.y);
  float n_xyz = mix(n_yz.x, n_yz.y, fade_xyz.x);
  return 2.2 * n_xyz;
}

mat3 rotation3dY(float angle) {
  float s = sin(angle);
  float c = cos(angle);
  return mat3(c, 0.0, -s, 0.0, 1.0, 0.0, s, 0.0, c);
}

vec3 rotateY(vec3 v, float angle) {
  return rotation3dY(angle) * v;
}

void main() {
  float t = uTime * uSpeed;
  float distortion = pnoise((normal + t) * uNoiseDensity, vec3(10.0)) * uNoiseStrength;
  float angle = sin(uv.y * uFrequency + t) * uAmplitude;

  vec3 pos = position + (normal * distortion);
  pos = rotateY(pos, angle);

  vPos = pos;
  vNormal = normalize(uNormalMatrix * normal);

  // Undeformed view position for physical lighting consistency (sphere/vertex.glsl line 242)
  vViewPosition = -(uModelViewMatrix * vec4(position, 1.0)).xyz;

  gl_Position = uProjectionMatrix * uModelViewMatrix * vec4(pos, 1.0);
}
`;

const SPHERE_FRAGMENT_SHADER = `
precision highp float;

varying vec3 vPos;
varying vec3 vNormal;
varying vec3 vViewPosition;

uniform vec3 uColor1; // #C6C3ED (1st most dominant - Periwinkle Lavender)
uniform vec3 uColor2; // #D98BD5 (2nd most dominant - Orchid Magenta)
uniform vec3 uColor3; // #FDD7D4 (3rd most dominant - Soft Blush Peach)

void main() {
  // Exact 3D distance from sphere center (from sphere/fragment.glsl)
  float distanceToCenter = distance(vPos, vec3(0.0, 0.0, 0.0));

  // 1. Start with #C6C3ED as the dominant base across the main sphere surface & lower-left folds
  //    Mix in #FDD7D4 (3rd dominant) only on the upper-right / far-right bulge of the sphere
  float peachLobe = smoothstep(-0.28, 0.38, -vPos.x + (vPos.y - 0.75) * 0.45)
                  * smoothstep(0.92, 1.28, distanceToCenter);
  vec3 outerSurface = mix(uColor1, uColor3, clamp(peachLobe, 0.0, 0.92));

  // 2. Sweep #D98BD5 (2nd most dominant) prominently through the diagonal 3D wave valley
  //    and across the lower-left overlapping fold contours
  float valleyFactor = smoothstep(0.58, 1.14, distanceToCenter);
  vec3 diffuseColor = mix(uColor2, outerSurface, valleyFactor);

  // Preserve distinct 3D step contrast on the bottom-left overlapping sphere folds
  float foldAccent = (1.0 - smoothstep(-0.35, 0.55, vPos.y)) * 0.38;
  diffuseColor = mix(diffuseColor, mix(uColor1, uColor2, 0.45), foldAccent);

  gl_FragColor = vec4(clamp(diffuseColor, 0.0, 1.0), 1.0);
}
`;

function hexToRgbNorm(hex) {
    const clean = hex.replace('#', '').trim();
    const num = parseInt(clean, 16);
    return [
        ((num >> 16) & 255) / 255,
        ((num >> 8) & 255) / 255,
        (num & 255) / 255,
    ];
}

function lookAtMatrix(eye, target, up) {
    const zx = eye[0] - target[0];
    const zy = eye[1] - target[1];
    const zz = eye[2] - target[2];
    const zLen = Math.hypot(zx, zy, zz) || 1;
    const z0 = zx / zLen, z1 = zy / zLen, z2 = zz / zLen;

    let xx = up[1] * z2 - up[2] * z1;
    let xy = up[2] * z0 - up[0] * z2;
    let xz = up[0] * z1 - up[1] * z0;
    const xLen = Math.hypot(xx, xy, xz) || 1;
    xx /= xLen; xy /= xLen; xz /= xLen;

    const y0 = z1 * xz - z2 * xy;
    const y1 = z2 * xx - z0 * xz;
    const y2 = z0 * xy - z1 * xx;

    return new Float32Array([
        xx, y0, z0, 0,
        xy, y1, z1, 0,
        xz, y2, z2, 0,
        -(xx * eye[0] + xy * eye[1] + xz * eye[2]),
        -(y0 * eye[0] + y1 * eye[1] + y2 * eye[2]),
        -(z0 * eye[0] + z1 * eye[1] + z2 * eye[2]),
        1,
    ]);
}

function multiplyMat4(a, b) {
    const out = new Float32Array(16);
    for (let i = 0; i < 4; i++) {
        for (let j = 0; j < 4; j++) {
            out[j * 4 + i] =
                a[0 * 4 + i] * b[j * 4 + 0] +
                a[1 * 4 + i] * b[j * 4 + 1] +
                a[2 * 4 + i] * b[j * 4 + 2] +
                a[3 * 4 + i] * b[j * 4 + 3];
        }
    }
    return out;
}

function perspectiveZoomMatrix(fovDeg, aspect, near, far, zoom) {
    const f = (1.0 / Math.tan((fovDeg * Math.PI) / 360.0)) * zoom;
    const nf = 1.0 / (near - far);
    return new Float32Array([
        f / aspect, 0, 0, 0,
        0, f, 0, 0,
        0, 0, (far + near) * nf, -1,
        0, 0, 2 * far * near * nf, 0,
    ]);
}

function buildSphereGeometry(radius, widthSegments, heightSegments) {
    const vertexCount = (widthSegments + 1) * (heightSegments + 1);
    const positions = new Float32Array(vertexCount * 3);
    const normals = new Float32Array(vertexCount * 3);
    const uvs = new Float32Array(vertexCount * 2);

    let pIdx = 0, nIdx = 0, uIdx = 0;
    for (let iy = 0; iy <= heightSegments; iy++) {
        const v = iy / heightSegments;
        const theta = v * Math.PI;
        const sinTheta = Math.sin(theta);
        const cosTheta = Math.cos(theta);

        for (let ix = 0; ix <= widthSegments; ix++) {
            const u = ix / widthSegments;
            const phi = u * Math.PI * 2;
            const nx = -Math.cos(phi) * sinTheta;
            const ny = cosTheta;
            const nz = Math.sin(phi) * sinTheta;

            positions[pIdx++] = radius * nx;
            positions[pIdx++] = radius * ny;
            positions[pIdx++] = radius * nz;

            normals[nIdx++] = nx;
            normals[nIdx++] = ny;
            normals[nIdx++] = nz;

            uvs[uIdx++] = u;
            uvs[uIdx++] = 1.0 - v;
        }
    }

    const indices = new Uint32Array(widthSegments * heightSegments * 6);
    let iIdx = 0;
    for (let iy = 0; iy < heightSegments; iy++) {
        for (let ix = 0; ix < widthSegments; ix++) {
            const a = iy * (widthSegments + 1) + ix + 1;
            const b = iy * (widthSegments + 1) + ix;
            const c = (iy + 1) * (widthSegments + 1) + ix;
            const d = (iy + 1) * (widthSegments + 1) + ix + 1;

            indices[iIdx++] = a;
            indices[iIdx++] = b;
            indices[iIdx++] = d;
            indices[iIdx++] = b;
            indices[iIdx++] = c;
            indices[iIdx++] = d;
        }
    }

    return { positions, normals, uvs, indices };
}

function compile(gl, type, src) {
    const s = gl.createShader(type);
    gl.shaderSource(s, src);
    gl.compileShader(s);
    if (!gl.getShaderParameter(s, gl.COMPILE_STATUS)) {
        gl.deleteShader(s);
        return null;
    }
    return s;
}

export function renderStaticHejHeroGradient() {
    const hero = document.querySelector('.hero-gradient-surface');
    if (!hero) return;

    const canvas = document.createElement('canvas');
    canvas.width = 1600;
    canvas.height = 960;

    const gl = canvas.getContext('webgl', {
        alpha: false,
        depth: true,
        antialias: true,
        preserveDrawingBuffer: true,
    });
    if (!gl) return;

    const extUint = gl.getExtension('OES_element_index_uint');
    const vs = compile(gl, gl.VERTEX_SHADER, SPHERE_VERTEX_SHADER);
    const fs = compile(gl, gl.FRAGMENT_SHADER, SPHERE_FRAGMENT_SHADER);
    if (!vs || !fs) return;

    const prog = gl.createProgram();
    gl.attachShader(prog, vs);
    gl.attachShader(prog, fs);
    gl.linkProgram(prog);
    if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) return;
    gl.useProgram(prog);

    const segs = extUint ? 192 : 120;
    const { positions, normals, uvs, indices } = buildSphereGeometry(1.0, segs, segs);

    function bindAttrib(name, data, size) {
        const loc = gl.getAttribLocation(prog, name);
        if (loc < 0) return;
        const buf = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buf);
        gl.bufferData(gl.ARRAY_BUFFER, data, gl.STATIC_DRAW);
        gl.enableVertexAttribArray(loc);
        gl.vertexAttribPointer(loc, size, gl.FLOAT, false, 0, 0);
    }

    bindAttrib('position', positions, 3);
    bindAttrib('normal', normals, 3);
    bindAttrib('uv', uvs, 2);

    const idxBuf = gl.createBuffer();
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, idxBuf);
    if (extUint) {
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, indices, gl.STATIC_DRAW);
    } else {
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indices), gl.STATIC_DRAW);
    }

    // Exact camera and mesh props from HoverGradient ("Hej" card in TextBox_test.Oe5TqQyo.mjs):
    // cDistance: 12.6, cPolarAngle: 30 deg, cAzimuthAngle: 0 deg, cameraZoom: 10.1, positionX: 0.3
    const cDistance = 12.6;
    const polarRad = (30 * Math.PI) / 180;
    const azimuthRad = (0 * Math.PI) / 180;
    const eye = [
        cDistance * Math.sin(polarRad) * Math.sin(azimuthRad),
        cDistance * Math.cos(polarRad),
        cDistance * Math.sin(polarRad) * Math.cos(azimuthRad),
    ];

    const viewMatrix = lookAtMatrix(eye, [0, 0, 0], [0, 1, 0]);
    const modelMatrix = new Float32Array([
        1, 0, 0, 0,
        0, 1, 0, 0,
        0, 0, 1, 0,
        0.3, 0, 0, 1, // positionX: 0.3
    ]);
    const modelViewMatrix = multiplyMat4(viewMatrix, modelMatrix);
    const projMatrix = perspectiveZoomMatrix(45, canvas.width / canvas.height, 0.1, 100.0, 10.1);

    gl.uniformMatrix4fv(gl.getUniformLocation(prog, 'uProjectionMatrix'), false, projMatrix);
    gl.uniformMatrix4fv(gl.getUniformLocation(prog, 'uModelViewMatrix'), false, modelViewMatrix);
    gl.uniformMatrix3fv(
        gl.getUniformLocation(prog, 'uNormalMatrix'),
        false,
        new Float32Array([
            modelViewMatrix[0], modelViewMatrix[1], modelViewMatrix[2],
            modelViewMatrix[4], modelViewMatrix[5], modelViewMatrix[6],
            modelViewMatrix[8], modelViewMatrix[9], modelViewMatrix[10],
        ])
    );

    // Exact uniforms from HoverGradient ("Hej" card)
    gl.uniform1f(gl.getUniformLocation(prog, 'uTime'), 4.5);
    gl.uniform1f(gl.getUniformLocation(prog, 'uSpeed'), 0.1);
    gl.uniform1f(gl.getUniformLocation(prog, 'uNoiseDensity'), 1.1);
    gl.uniform1f(gl.getUniformLocation(prog, 'uNoiseStrength'), 1.0);
    gl.uniform1f(gl.getUniformLocation(prog, 'uFrequency'), 5.5);
    gl.uniform1f(gl.getUniformLocation(prog, 'uAmplitude'), 5.0);

    // User's color hierarchy:
    // 1st dominant: #C6C3ED (uColor1)
    // 2nd dominant: #D98BD5 (uColor2)
    // 3rd dominant: #FDD7D4 (uColor3)
    gl.uniform3fv(gl.getUniformLocation(prog, 'uColor1'), hexToRgbNorm('#C6C3ED'));
    gl.uniform3fv(gl.getUniformLocation(prog, 'uColor2'), hexToRgbNorm('#D98BD5'));
    gl.uniform3fv(gl.getUniformLocation(prog, 'uColor3'), hexToRgbNorm('#FDD7D4'));

    gl.viewport(0, 0, canvas.width, canvas.height);
    gl.clearColor(0.776, 0.765, 0.929, 1.0);
    gl.enable(gl.DEPTH_TEST);
    gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

    gl.drawElements(
        gl.TRIANGLES,
        indices.length,
        extUint ? gl.UNSIGNED_INT : gl.UNSIGNED_SHORT,
        0
    );

    // Bake into a static image URL and immediately free the WebGL context
    const dataUrl = canvas.toDataURL('image/png');
    hero.style.backgroundImage = `url("${dataUrl}")`;
    hero.style.backgroundSize = 'cover';
    hero.style.backgroundPosition = 'center';

    const loseCtx = gl.getExtension('WEBGL_lose_context');
    if (loseCtx) loseCtx.loseContext();
}
