import OrganizationTypeEnum from "@/enums/organization-type.enum";

interface OrganizationInterface {
    type: OrganizationTypeEnum
    inn: number
    name: string
}

export default OrganizationInterface
