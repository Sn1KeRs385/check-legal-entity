import OrganizationInterface from "@/types/organization.interface";

interface PhysicalPersonInterface {
    id: number
    inn: number
    firstName: string
    secondName: string
    lastName: string
    organizations: OrganizationInterface[]
}

export default PhysicalPersonInterface
