# Callback Api Session Storage

## 1.Create Global code

### Method:POST

### API endpoint:

https://webiste/veda/v1/global-code

[//]: # (####headers)

[//]: # (token : cad38277b27a18b06c5252294723c5239a09e32a)

##### parameters

````ts
export interface Data {
    type: 'utils' | 'plugins';
    scss: String;
    js: String;
    name: String;
}

````

Response

````ts
export interface Response {
    data: Data
    /** messege là tin nhắn trả lại trên sever*/
    message: string
    status: string
}

export interface Data {
    id: string
}

````

## 2.Delete global code

### Method:DELETE

### API endpoint:

https://webiste/veda/v1/global-code/:id

##### parameters

````ts
export interface parameters {
    id: string;
}


````

#### Response

````ts
export interface Statistic {
    data: []
    /** messege là tin nhắn trả lại trên sever*/
    message: string
    status: string
}
````

## 3.GET global codes

### Method:GET

### API endpoint:

https://webiste/veda/v1/global-code

#### Param

param | type | description
--- | --- | ---
limit | string |
page | string |
s | string |

#### Response
se
````ts
export interface Statistic {
    data: Data
    /** messege là tin nhắn trả lại trên sever*/
    message: string
    status: string
}

export interface Data {
    id: Number
    name: string
    scss: string
    js: string
    type: string
}
````

## 4.Get global code

### Method:POST

### API endpoint:

https://webiste/veda/v1/global-code/:id

##### parameters

#### Param

Response

````ts
export interface Response {
    data: Data
    /** messege là tin nhắn trả lại trên sever*/
    message: string
    status: string
}

export interface Data {
    shopName: string
}

````

## 5.Get global code url

### Method:GET

### API endpoint:

https://webiste/veda/v1/global-code-url

##### parameters


#### Response

````ts
export interface Statistic {
    data: []
    /** messege là tin nhắn trả lại trên sever*/
    message: string
    status: string
}

export interface Data {
    scss: string
    js: string
}
````