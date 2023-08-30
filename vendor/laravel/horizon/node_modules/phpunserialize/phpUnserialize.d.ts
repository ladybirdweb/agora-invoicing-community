// Type definitions for phpUnserialize
type phpscalar = string | number | boolean | null;
type phparray = Array<any> | Record<string, any>;
export type unserialized = phpscalar | phparray;
export function phpUnserialize(phpstr: string): unserialized;
