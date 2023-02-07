const BASE_URL = $('meta[name="baseUrl"]').attr("content")

const dataColumnTable = (data = []) => {
    let result = [];
    data.forEach((field) => result.push({ data: field }));
    return result;
}