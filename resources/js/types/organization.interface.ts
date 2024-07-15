import OrganizationTypeEnum from "@/enums/organization-type.enum";

interface OrganizationInterface {
    id: number
    type: OrganizationTypeEnum
    inn: number
    name: string
}

export default OrganizationInterface
